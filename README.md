#Writer in Telegram for Zend Log

Example `application.ini`:

```ini
resources.log.telegram.writerNamespace = "TelegramLog_Writer"
resources.log.telegram.writerName = "Telegram"
resources.log.telegram.writerParams.token = "123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11"
resources.log.telegram.writerParams.chat_id = "@my_project_alerts"
```

I recommend using the formatter like this:

```ini
resources.log.telegram.formatterName = "Simple"
resources.log.telegram.formatterParams.format = '%timestamp% %priorityName% in [%file%:%line%] #%errno% %message%' PHP_EOL'%context%' PHP_EOL PHP_EOL
```