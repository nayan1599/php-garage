
 

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
<title><?= isset($page_title) ? $page_title : 'গ্যারেজ সিস্টেম' ?></title>
  <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="/assets/css/style.css"> 
</head>
<body>

 

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">
      🚗 গ্যারেজ
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
      aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="topNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=profile">
            👤 প্রোফাইল
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php" onclick="return confirm('আপনি লগআউট করতে চান?');">
            🔒 লগআউট
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

