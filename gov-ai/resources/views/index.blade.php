<!DOCTYPE html>
<html>
<head>
    <title>Bantuan Doc Checker</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="navbar">
    <h2>CheckBantuan.ai</h2>
</div>

<div class="container">
    <h1>Validate Your Bantuan Documents</h1>
    <p>Upload your IC or Salary Slip for instant AI analysis</p>

    <div class="upload-box">
        <input type="file" id="fileInput">
        <button onclick="uploadFile()">Analyze Document →</button>
    </div>

    <div id="result"></div>
</div>

<script>
async function uploadFile() {
    let file = document.getElementById('fileInput').files[0];
    let formData = new FormData();
    formData.append('document', file);

    let res = await fetch('/check', {
        method: 'POST',
        body: formData
    });

    let data = await res.json();

    document.getElementById('result').innerHTML =
        `<h3>Detected: ${data.document_type}</h3>
         <p><b>Valid:</b> ${data.valid}</p>
         <p><b>Issues:</b> ${data.issues.join(", ")}</p>
         <p><b>Suggestions:</b> ${data.suggestions.join(", ")}</p>`;
}
</script>

</body>
</html>
