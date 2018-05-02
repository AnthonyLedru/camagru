<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/include/autoload.include.php';

try {
    if (!isset($_SESSION['user'])) {
        $page = new Webpage("Change password");
        $page->appendJsUrl('js/changePassword.js');
        if (isset($_GET['token'])) {
            if (($user = User::createFromPasswordToken($_GET['token'])) !== false) {
                $page->appendContent(<<<HTML
                
                    <div class="hero-body">
                        <div class="container">
                            <div class="columns has-text-centered">
                                <div class="column is-fullWidth">
                                    <p class="title is-1">Change your password</p>
                                    <br>
                                </div>
                            </div>
                            <div class="columns is-centered has-text-centered is-vcentered">
                                <div class="column is-half">
                                    <form name="change_password_form" id="change_password_form" method="POST">
                                        <div class="field is-horizontal">
                                            <div class="field-body">
                                                <div class="field">
                                                    <p class="control is-expanded">
                                                    <input class="input" name="password" type="password" placeholder="New password">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field is-horizontal">
                                            <div class="field-body">
                                                <div class="field">
                                                    <p class="control is-expanded">
                                                    <input class="input" name="passwordConf" type="password" placeholder="New password confirmation">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <input name="passwordToken" type="hidden" value="{$user->getPasswordToken()}">
                                    </form>
                                    <br>
                                    <button class="button is-dark" form="change_password_form" type="submit">Change password</button>
                                </div>
                            </div>
                        </div>
                    </div>
HTML
                );
            } else {
                header("Location:index.php");
            }
        } else {

            $page->appendContent(<<<HTML

                    <div class="hero-body">
                        <div class="container">
                            <div class="columns has-text-centered">
                                <div class="column is-fullWidth">
                                    <p class="title is-1">Reset your password</p>
                                    <br>
                                </div>
                            </div>
                            <div class="columns is-centered has-text-centered is-vcentered">
                                <div class="column is-half">
                                    <form name="reset_password_form" id="reset_password_form" method="POST">
                                        <input class="input" name="mail" type="mail" placeholder="Your mail">
                                    </form>
                                    <br>
                                    <button class="button is-dark" form="reset_password_form" type="submit">Send reset password mail</button>
                                </div>
                            </div>
                        </div>
                    </div>
HTML
            );
        }
    } else
        header("Location:index.php");
} catch (Exception $e) {
    $page = new WebPageError("Error");
    $page->appendError("{$e->getMessage()}");
}

echo $page->toHTML();