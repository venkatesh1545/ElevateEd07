from flask import Flask, request, jsonify,render_template
from flask_cors import CORS
from bs4 import BeautifulSoup
import requests
from transformers import pipeline
import PyPDF2  # Replacing pdfplumber with PyPDF2
from docx import Document
import os
from werkzeug.utils import secure_filename

app = Flask(__name__)
CORS(app)
# Load summarization model
summarizer = pipeline("summarization", model="Falconsai/text_summarization")

UPLOAD_DOCUMENTS_FOLDER = os.path.join(os.getcwd(), '/statics/documents')

os.makedirs(UPLOAD_DOCUMENTS_FOLDER, exist_ok=True)

# Allowed file extensions
ALLOWED_DOCUMENT_EXTENSIONS = {'pdf', 'docx', 'txt'}

def allowed_file(filename, allowed_extensions):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in allowed_extensions

@app.route('/upload', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        return jsonify({"error": "No file part in the request"}), 400

    file = request.files['file']
    source_type = request.form.get('type')

    if file.filename == '':
        return jsonify({"error": "No selected file"}), 400

    # Determine the upload folder based on the source type
    if source_type in ['docx', 'pdf', 'txt']:
        upload_folder = UPLOAD_DOCUMENTS_FOLDER
        allowed_extensions = ALLOWED_DOCUMENT_EXTENSIONS
    else:
        return jsonify({"error": "Invalid source type"}), 400

    if file and allowed_file(file.filename, allowed_extensions):
        filename = secure_filename(file.filename)
        file_path = os.path.join(upload_folder, filename)
        file.save(file_path)
        return jsonify({"filePath": file_path})
    else:
        return jsonify({"error": "File type not allowed"}), 400

def extract_web_content(url):
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')
    
    for tag in soup(['script', 'style', 'nav', 'footer', 'aside']):
        tag.decompose()
    
    paragraphs = soup.find_all('p')
    text = " ".join([para.get_text() for para in paragraphs])
    
    if not text.strip():
        raise ValueError("No content found on the webpage")
    
    return text


def read_document(file_path):
    if not os.path.exists(file_path):
        raise FileNotFoundError(f"File not found: {file_path}")
    
    if file_path.endswith('.pdf'):
        with open(file_path, 'rb') as file:
            reader = PyPDF2.PdfReader(file)
            text = " ".join(page.extract_text() for page in reader.pages)
    elif file_path.endswith('.docx'):
        doc = Document(file_path)
        text = " ".join(paragraph.text for paragraph in doc.paragraphs)
    elif file_path.endswith('.txt'):
        with open(file_path, 'r') as file:
            text = file.read()
    else:
        raise ValueError("Unsupported file format")
    
    if not text.strip():
        raise ValueError("No text found in the document")
    
    return text

def generate_summary(text, max_length=150, min_length=30):
    """Generate a summary using Hugging Face Transformers."""
    # Split the text into chunks of ~1000 tokens each
    chunk_size = 1000
    chunks = [text[i:i + chunk_size] for i in range(0, len(text), chunk_size)]

    summaries = []
    for chunk in chunks:
        summary = summarizer(chunk, max_length=max_length, min_length=min_length, do_sample=False)
        summaries.append(summary[0]['summary_text'])

    # Combine the summaries into a single summary
    combined_summary = " ".join(summaries)
    return combined_summary

@app.route('/summarize', methods=['POST'])
def summarize():
    data = request.json
    print("Received data:", data)
    
    source_type = data.get('type')
    source = data.get('source')

    if not source_type or not source:
        return jsonify({"error": "Missing 'type' or 'source' in request"}), 400

    try:
        if source_type == 'web':
            text = extract_web_content(source)
            print(f"Extracted text length: {len(text)}")  # Log the length of the text
        elif source_type in ['docx', 'pdf', 'txt']:
            text = read_document(source)
        else:
            return jsonify({"error": "Invalid source type"}), 400

        summary = generate_summary(text)
        return jsonify({"summary": summary})
    except Exception as e:
        return jsonify({"error": str(e)}), 500
@app.route('/')
def home():
    return render_template('index.html')
if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=6550)