Плагин для WordPress

Создание форм для выполнения аякс запросов в админке

**Установка**

с помощью wp-cli и afragen/github-updater
1. установить github-updater

        wp plugin install --activate https://github.com/afragen/github-updater/archive/master.zip

2. установить и активировать плагин 

        wp plugin install-git https://github.com/4selin/wp-ajax-actions && wp plugin activate wp-ajax-actions

**Использование**

в `functions.php` добавить действие 

    add_action('wpaa_menu_page', 'wpaa_menu_page_html');
    
и добавить функцию   

    function wpaa_menu_page_html(){
    
        wpaa()->create_form('Title', 'example_fnc');
    }
    
Параметры для `wpaa()->create_form()`
