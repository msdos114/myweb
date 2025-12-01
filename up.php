<?php
// up.php - 处理文件上传
// 设置编码
header('Content-Type: text/html; charset=UTF-8');

// 上传配置
$uploadDir = 'uploads/'; // 上传文件保存目录
$maxFileSize = 50 * 1024 * 1024; // 最大文件大小 50MB
$allowedTypes = array(
    'video/mp4',
    'video/avi',
    'video/mov',
    'video/wmv',
    'video/flv',
    'video/webm',
    'application/x-shockwave-flash',
    'application/vnd.adobe.flash.movie'
);
$allowedExtensions = array('swf', 'flv', 'mp4', 'avi', 'mov', 'wmv', 'webm');

// 创建上传目录（如果不存在）
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// 检查是否有文件上传
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('错误：无效的请求方法');
}

if (!isset($_FILES['largeFile'])) {
    die('错误：没有文件被上传');
}

$file = $_FILES['largeFile'];
$fileName = $file['name'];
$fileSize = $file['size'];
$fileTmpName = $file['tmp_name'];
$fileError = $file['error'];

// 检查上传错误
if ($fileError !== UPLOAD_ERR_OK) {
    switch ($fileError) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            die('错误：文件太大，超过服务器限制');
        case UPLOAD_ERR_PARTIAL:
            die('错误：文件只有部分被上传');
        case UPLOAD_ERR_NO_FILE:
            die('错误：没有文件被上传');
        default:
            die('错误：上传过程中发生未知错误');
    }
}

// 检查文件大小
if ($fileSize > $maxFileSize) {
    die('错误：文件大小超过限制（最大50MB），如需上传大文件请<a href="./link.html">联系管理员</a>');
}

// 检查文件类型
$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
if (!in_array($fileExtension, $allowedExtensions)) {
    die('错误：不支持的文件类型。只允许上传：' . implode(', ', $allowedExtensions) . ' 格式的文件');
}

// 生成安全的文件名
$safeFileName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", $fileName);
$safeFileName = time() . '_' . $safeFileName; // 添加时间戳防止重名
$uploadPath = $uploadDir . $safeFileName;

// 移动上传的文件
if (move_uploaded_file($fileTmpName, $uploadPath)) {
    // 上传成功 - 使用与网站一致的HTML结构
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>上传成功 - SYSTEM-MS-DOS</title>
<link id="theme-link" rel="stylesheet" href="./style.css">
</head>
<body>
<div id="all">
<div id="top">
<a href="./index.html"><img src="./top.bmp" border="0"></a>
<div id="nav">
<p>
<a href="./index.html">主页</a>
<a href="./video.html">媒体中心</a>
<a href="./download.html">下载</a>
<a href="https://www.bilibili.com/video/BV1mG3tzME1B/?spm_id_from=333.337.search-card.all.click">WindowsLiveMessenger</a>
<a href="https://www.bilibili.com/video/BV15tgLz1EpK/?spm_id_from=333.337.search-card.all.click">ICQ（不再受支持）</a>
<a href="./link.html">联系</a>
<a href="./feedback.html">反馈</a>
</p>
</div>
</div>
<div id="main">
<br>
<center><h1>上传成功</h1></center>
<div class="h">
    <p>文件上传成功</p>
</div>
<p>您的文件 "' . htmlspecialchars($fileName) . '" 已成功上传！</p>
<p>保存为: ' . htmlspecialchars($safeFileName) . '</p>
<p>文件大小: ' . round($fileSize / 1024 / 1024, 2) . ' MB</p>
<p><a href="./video.html">返回媒体中心</a></p>
<br>
</div>
</div>
</body></html>';
} else {
    // 错误页面也保持相同结构
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>上传失败 - SYSTEM-MS-DOS</title>
<link id="theme-link" rel="stylesheet" href="./style.css">
</head>
<body>
<div id="all">
<div id="top">
<a href="./index.html"><img src="./top.bmp" border="0"></a>
<div id="nav">
<p>
<a href="./index.html">主页</a>
<a href="./video.html">媒体中心</a>
<a href="./download.html">下载</a>
<a href="https://www.bilibili.com/video/BV1mG3tzME1B/?spm_id_from=333.337.search-card.all.click">WindowsLiveMessenger</a>
<a href="https://www.bilibili.com/video/BV15tgLz1EpK/?spm_id_from=333.337.search-card.all.click">ICQ（不再受支持）</a>
<a href="./link.html">联系</a>
<a href="./feedback.html">反馈</a>
</p>
</div>
</div>
<div id="main">
<br>
<center><h1>上传失败</h1></center>
<div class="h">
    <p>文件保存失败</p>
</div>
<p>错误：文件保存失败，请检查目录权限或联系管理员。</p>
<p><a href="./video.html">返回媒体中心</a></p>
<br>
</div>
</div>
</body></html>';
}
?>