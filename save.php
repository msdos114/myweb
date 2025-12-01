<?php
// 设置页面编码为UTF-8，确保中文显示正常
header("Content-Type: text/html; charset=UTF-8");

// 检查是否是POST请求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取表单提交的数据
    $contact = isset($_POST['name']) ? trim($_POST['name']) : '';
    $feedback = isset($_POST['feed']) ? trim($_POST['feed']) : '';

    // 简单验证数据
    if (empty($feedback)) {
        echo "请填写建议内容！<br>";
        echo "<a href='feedback.html'>返回</a>";
        exit;
    }

    // 准备要保存的数据
    $data = "-------------------------\n";
    $data .= "提交时间：" . date("Y-m-d H:i:s") . "\n";
    $data .= "联系方式：" . $contact . "\n";
    $data .= "建议内容：\n" . $feedback . "\n\n";

    // 保存到文件（建议将feedback_data.txt放在网站根目录外或设置权限保护）
    $file = fopen("feedback_data.txt", "a");
    if ($file) {
        fwrite($file, $data);
        fclose($file);
        echo "感谢您的反馈！我们会认真考虑您的建议。<br>";
        echo "<a href='index.html'>返回主页</a>";
    } else {
        echo "提交失败，请稍后再试！<br>";
        echo "<a href='feedback.html'>返回</a>";
    }
} else {
    // 不是POST请求，跳转到反馈页面
    header("Location: feedback.html");
    exit;
}
?>