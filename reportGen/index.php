<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report Generator</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-5">

<div class="container">
  <h2 class="mb-4">Generate Report</h2>
  <form action="formHandler.php" method="POST">
    <div class="mb-3">
      <label for="reportName" class="form-label">Report Name</label>
      <input type="text" id="reportName" name="reportName" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="os" class="form-label">Operating System</label>
      <select id="os" name="os" class="form-select">
        <option value="l">Linux/macOS</option>
        <option value="w">Windows</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Generate</button>
  </form>
</div>

</body>
</html>
