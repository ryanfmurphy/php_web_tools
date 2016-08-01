<?php
class ExampleNestedController extends Controller {

    public static function action_hello_nested() {
        return "Hello, welcome to my nested route!";
    }

    public static function action_index() {
        return "Hello, here's the nested index page";
    }

}
