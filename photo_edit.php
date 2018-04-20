<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Photo Editing");

if (isset($_SESSION['user'])) {
    $user = User::createFromLogin($_SESSION['user']['login']);
    $page->appendContent(<<<HTML

                <div class="hero-body">
                    <div class="has-text-centered" id="cam_page">
                        <div class="columns is-vcentered">
                            <div class="column is-fullWidth">
                                <p class="title is-1">Photo editing</p>
                                <br>
                            </div>
                        </div>
                        <div class="columns is-vcentered">
                            <div class="column cam_menu">
                                <h1 class="title is-2">Cam</h1>
                                <video autoplay="true" id="video"></video>
                                <br>
                                <input type="hidden" name="userId" value="{$user->getUserId()}" id="userId">
                                <button class="button is-dark" id="take_photos" type="submit">Take photo</button>
                            </div>
                            <div class="column cam_menu">
                                <h1 class="title is-2">Photo list</h1>
                                <div class="has-text-centered" id="photo_list"></div>
                                <br>
                                <button class="button is-dark" id="cancel_photo" type="submit">Cancel</button>
                                <input name="file" class="button is-dark" id="file_input" type="file">
                                <label for="file_input" class="button is-dark">Upload</label>
                                <button class="button is-dark" id="save_photo" type="submit">Save photo(s)</button>
                            </div>
                        </div>
                    </div>
                </div>
HTML
    );

} else {

    $page->appendContent(<<<HTML

            <div class="hero-body">
                <div class="container has-text-centered">
                    <div class="columns is-vcentered">
                        <div class="column is-fullWidth">
                            <h1 class="title">Forbidden</h1>
                            <p>You must be logged in to access this page</p>
                        </div>
                    </div>
                </div>
            </div>
HTML
    );

}

echo $page->toHTML();