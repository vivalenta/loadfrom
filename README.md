# loader video from youtube + M3U8 + link 

need (wget+ffmpeg+youtube-dl)

currently Ukrainian and English location only!

currently chandge in config, not avto

## Config
config file is config.php, obviously

`include 'lang_ua.php'; `select location

`include 'lang_en.php'; `English 

`$downloadsDirectory = '/var/www/ltest.com/downloads';` patch to folder downloads

`$mySite = 'test.com';` site name, example "Home loader"
 
`$mySiteHttps = 'https://test.com';` full url of site

`$pass = 'pass';` pass key (api key) for view log or backup

`$logfile = '/var/log/nginx/test.com-ssl-access.log';` nginx log file (currently nginx only)


## Screenshots
![Screenshot home](https://github.com/vivalenta/loadfrom/raw/master/img/2018-12-07%2014-34-43.png)

![Screenshot add download from youtube](https://raw.github.com/vivalenta/loadfrom/master/img/2018-12-07%2014-35-35.png)
