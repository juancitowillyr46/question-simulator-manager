<?php
/**
 * Default English Lexicon Entries for UICMPGenerator
 *
 * @package uicmpgenerator
 * @subpackage lexicon
 */
$_lang['uicmpgenerator'] = 'UICMPGenerator';
$_lang['uicmpgenerator.desc'] = 'Generate CMP code';
$_lang['uicmpgenerator.description'] = 'Generate CMP Code';
$_lang['uicmpgenerator.management'] = 'UICMPGenerator';
$_lang['uicmpgenerator.management_desc'] = 'Generate CMP Code';

$_lang['uicmpgenerator.package'] = 'Название пакета';
$_lang['uicmpgenerator.database'] = 'Название БД';
$_lang['uicmpgenerator.table_prefix'] = 'Префикс';
$_lang['uicmpgenerator.build_scheme'] = 'Создать схему';
$_lang['uicmpgenerator.build_package'] = 'Создать пакет';
$_lang['uicmpgenerator.edit_package'] = 'Редактировать пакет';
$_lang['uicmpgenerator.create_date'] = 'Дата создания';
$_lang['uicmpgenerator.last_ran'] = 'Последняя генерация';
$_lang['uicmpgenerator.search'] = 'Поиск...';
$_lang['uicmpgenerator.refresh'] = 'Обновить';

$_lang['uicmpgenerator.tab_scheme'] = 'XML schema';
$_lang['uicmpgenerator.tab_modeler'] = 'Modeler scheme';

$_lang['uicmpgenerator.btn.build_package'] = 'Сгенерировать код';

$_lang['uicmpgenerator.title.create_connection'] = 'Добавить связь';
$_lang['uicmpgenerator.title.extending'] = 'Расширить таблицу';
$_lang['uicmpgenerator.title.edit_table_name'] = 'Редактировать название таблицы';

$_lang['uicmpgenerator.label.relation_type'] = 'Тип связи';
$_lang['uicmpgenerator.label.dependence_type'] = 'Тип зависимости';
$_lang['uicmpgenerator.label.alias'] = 'Псевдоним для ';
$_lang['uicmpgenerator.label.extends'] = 'Расширять';
$_lang['uicmpgenerator.label.extends_name'] = 'Название новой таблицы';
$_lang['uicmpgenerator.label.table_name'] = 'Название таблицы';


$_lang['uicmpgenerator.create'] = 'Создать пакет';
$_lang['uicmpgenerator.err_nf'] = 'CMP не найден.';
$_lang['uicmpgenerator.err_ae'] = 'CMP Пакет с таким именем уже существует.';
$_lang['uicmpgenerator.err_ae_table'] = 'Ошибка! Данное название таблицы уже используется';
$_lang['uicmpgenerator.err_ns'] = 'Имя CMP Пакет не уточняется.';
$_lang['uicmpgenerator.err_ns_name'] = 'Пожалуйста, укажите имя пакета для UI CMP.';
$_lang['uicmpgenerator.err_required'] = 'Заполнение данного поля обязательно';
$_lang['uicmpgenerator.err_remove'] = 'Произошла ошибка при попытке удалить CMP пакет.';
$_lang['uicmpgenerator.err_save'] = 'Произошла ошибка при попытке сохранить.';
$_lang['uicmpgenerator.err_save_scheme'] = 'Ошибка при сохранении схемы';
$_lang['uicmpgenerator.remove'] = 'Удалить CMP пакет';
$_lang['uicmpgenerator.remove_confirm'] = 'Вы уверены, что хотите удалить этот CMP пакет?';
$_lang['uicmpgenerator.update'] = 'Обновить';
$_lang['uicmpgenerator.name'] = 'Название';
$_lang['uicmpgenerator.search...'] = 'Поиск...';


$_lang['uicmpgenerator.build'] = 'Создать CMP пакет';
$_lang['uicmpgenerator.build_confirm'] = 'Вы уверены, что хотите созать этот CMP пакет?';
$_lang['uicmpgenerator.update_schema'] = 'Обновить схему';
$_lang['uicmpgenerator.remove_update_schema'] = 'Вы уверены, что хотите обновить схему для этого CMP пакета?';
$_lang['uicmpgenerator.regenerate_code'] = 'Восстановить код';
$_lang['uicmpgenerator.remove_regenerate_schema'] = 'Вы уверены, что хотите восстановить весь код для этого CMP пакет? Все файлы будут удалены, а затем заново созданы.';

$_lang['uicmpgenerator.draw2d.menu_delete_segment'] = 'Удалить сегмент';
$_lang['uicmpgenerator.draw2d.menu_add_segment'] = 'Добавить сегмент';
$_lang['uicmpgenerator.draw2d.menu_extends'] = 'Расширить таблицу';
$_lang['uicmpgenerator.draw2d.menu_clear_all'] = 'Очистить';
$_lang['uicmpgenerator.options.relation1'] = 'Один к одному';
$_lang['uicmpgenerator.options.relation2'] = 'Один ко многим';
$_lang['uicmpgenerator.options.dependence1'] = 'Агрегирующая';
$_lang['uicmpgenerator.options.dependence2'] = 'Композиционная';

$_lang['uicmpgenerator.check.mess_nf_field'] = 'Не удалось найти  поле {{field}} в таблице {{table}}, возможно оно было удалено или изменено';
$_lang['uicmpgenerator.check.mess_nf_table'] = 'Не удалось найти таблицу ';
$_lang['uicmpgenerator.check.mess_add_field'] = 'Таблица {{table}} была обновлена, добавлено  новое поле {{field}}';
$_lang['uicmpgenerator.check.mess_dependence_nf_table'] = 'Связь между таблицей  {{table1}} и {{table2}} по полям {{field1}} -> {{field2}}  не создана т.к не найдена таблица {{table1}}';
$_lang['uicmpgenerator.check.mess_dependence_nf_field'] = 'Связь между таблицей  {{table1}} и {{table2}} по полям {{field1}} -> {{field2}}  не создана т.к не найдено поле {{field1}} в {{table1}}';