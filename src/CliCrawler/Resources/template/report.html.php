<html>
<head>
    <title>CliCrawler</title>
    <meta charset="utf-8">
</head>
<body>
<h3><?php echo $site ?></h3>
<table>
    <thead>
    <tr>
        <td>Url</td>
        <td>Quantity images</td>
    </tr>
    </thead>
    <tbody>
    <?php /** @var $urlCollector \CliCrawler\Url\UrlCollectorInterface */ ?>
    <?php foreach ($urlCollector->getUrlsSortedByQuantity() as $url): ?>
        <tr>
            <td><?php echo $url ?></td>
            <td><?php echo $urlCollector->getQuantity($url)?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
</body>
</html>