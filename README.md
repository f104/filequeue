## fileQueue

Компонент для MODx Revolution.
Реализует очередь файлов для обработки.

Доступные настройки (значения по умолчанию):
- Разрешённые к загрузке файлы (xls,xlsx,csv)
- Процессор для обработки файлов (default) 
- Директория для сохранения загруженных файлов ({assets_path}components/filequeue/upload/)

В {core_path}components/filequeue/processors/parser лежит базовый процессор для парсинга файлов.
Он открывает файл, считает количество строк на первом листе и пишет его в лог.
Этот процессор нужно расширять или копировать.

Файл для крона находиться здесь: {core_path}components/filequeue/elements/crontab