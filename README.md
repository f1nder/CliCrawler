CliCrawler
==========


Установка
---------
~~~
  git clone https://github.com/f1nder/CliCrawler.git
  cd CliCrawler
  curl http://getcomposer.org/installer | php
  php composer.phar install --dev
~~~

Запуск
--------
Запускать скрипт через bin/crawler [url] [--options]
####Возможные опции

  *  level - глубина сканирования, по умолчанию 10 
  *  debug - режим дебага, выводит информацию о каждом сканированном урле
  *  reportDir - дериктория в которую записывать отчет, по умолчанию это reports в корне

####Пример запуска
~~~
  bin/crawler http://symfony.com --debug --level=3
~~~

Тесты 
--------
Проектировалось и тестировалось с помощью довольно молодой утилиты [PHPSpec2](http://www.phpspec.net/)
####Выполнение тестов 
~~~
  bin/phpspec run
~~~
