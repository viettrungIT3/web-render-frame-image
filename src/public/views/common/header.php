<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta property="og:image" content="https://unisoftvietnam.com/wp-content/uploads/2023/01/cropped-logo-Unisoft.png">
	<!-- <meta property="og:url" content="https://unisoftvietnam.com/"> -->
    <link rel="shortcut icon" href="https://unisoftvietnam.com/wp-content/uploads/2023/01/cropped-logo-Unisoft-32x32.png" type="image/x-icon">
    <link rel="icon" href="https://unisoftvietnam.com/wp-content/uploads/2023/01/cropped-logo-Unisoft-32x32.png" sizes="32x32">
    <link rel="shortlink" href="https://unisoftvietnam.com/">

    <title><?php echo $page_title; ?></title>
    <?php echo $styles; ?>

    <style id="css-main-template">
        <?= load_css("layout"); ?>
    </style>
</head>

<body class="<?php echo $body_classes; ?>" <?php if (isset($is_ie) && $is_ie) : ?> data-browser='ie' <?php endif; ?>>
    <main id="page-wrapper">