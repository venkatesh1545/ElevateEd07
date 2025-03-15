document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("summarizeForm");
  const sourceType = document.getElementById("sourceType");
  const urlInputGroup = document.getElementById("urlInputGroup");
  const fileInputGroup = document.getElementById("fileInputGroup");
  const fileInput = document.getElementById("fileInput");
  const fileName = document.getElementById("fileName");
  const submitBtn = document.getElementById("submitBtn");
  const error = document.getElementById("error");
  const summary = document.getElementById("summary");
  const summaryText = document.getElementById("summaryText");
  const loader = document.getElementById("loader");

  // Toggle between URL and file input based on source type
  sourceType.addEventListener("change", () => {
    const isWeb = sourceType.value === "web";
    urlInputGroup.classList.toggle("hidden", !isWeb);
    fileInputGroup.classList.toggle("hidden", isWeb);
    fileInput.value = "";
    fileName.textContent = "Choose file";
  });

  // Update file name when file is selected
  fileInput.addEventListener("change", () => {
    fileName.textContent = fileInput.files[0]?.name || "Choose file";
  });

  // Handle form submission
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Reset states
    error.classList.add("hidden");
    summary.classList.add("hidden");

    const isWeb = sourceType.value === "web";
    const urlInput = document.getElementById("urlInput");

    // Validate input
    if (isWeb && !urlInput.value) {
      showError("Please enter a valid URL");
      return;
    }

    if (!isWeb && !fileInput.files[0]) {
      showError("Please select a file");
      return;
    }

    // Show loader and disable submit button
    loader.classList.remove("hidden");
    submitBtn.disabled = true;

    try {
      let sourcePath = isWeb ? urlInput.value : "";

      // Handle file upload if not web source
      if (!isWeb) {
        const file = fileInput.files[0];
        const formData = new FormData();
        formData.append("file", file);
        formData.append("type", sourceType.value);

        const uploadResponse = await fetch("http://localhost:5002/upload", {
          method: "POST",
          body: formData,
        });

        if (!uploadResponse.ok) {
          throw new Error("Failed to upload file");
        }

        const { filePath } = await uploadResponse.json();
        sourcePath = filePath;
      }

      // Get summary
      const summarizeResponse = await fetch(
        "http://localhost:5002/summarize",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            type: sourceType.value,
            source: sourcePath,
          }),
        }
      );

      if (!summarizeResponse.ok) {
        throw new Error("Failed to generate summary");
      }

      const data = await summarizeResponse.json();

      // Show summary
      summaryText.textContent = data.summary;
      summary.classList.remove("hidden");
    } catch (err) {
      showError(err.message);
    } finally {
      // Hide loader and enable submit button
      loader.classList.add("hidden");
      submitBtn.disabled = false;
    }
  });

  function showError(message) {
    error.textContent = message;
    error.classList.remove("hidden");
  }
});
