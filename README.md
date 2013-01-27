CliCrawler
==========
Запускать скрипт через bin/crawler [url] [--options]

Возможные опции
  level - глубина сканирования, по умолчанию 10
  debug - режим дебага, выводит информацию о каждом сканированном урле
  reportDir - дериктория в которую записывать отчет, по умолчанию это reports в корне

Пример запуска
  bin/crawler http://symfony.com --debug --level=3 --reportDir=/var/reports

