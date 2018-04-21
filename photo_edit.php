<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Photo Editing");

if (isset($_SESSION['user'])) {
    $user = User::createFromLogin($_SESSION['user']['login']);
    $page->appendContent(<<<HTML

            <div class="hero-body">
                <div class="" id="cam_page">
                    <div class="columns has-text-centered">
                        <div class="column is-fullWidth">
                            <p class="title is-1">Photo editing</p>
                            <br>
                        </div>
                    </div>
                    <div class="columns is-vcentered is-desktop">
                        <div class="column cam_menu">
                            <h1 class="title is-2 has-text-centered">Cam</h1>
                            <div class="columns is-gapless">
                                <div class="column is-four-fifths">
                                <div class="outer-container">
                                    <div id="inner_container">
                                        <video id="video" autoplay=true></video>
                                    </div>
                                </div>
                                </div>
                                <div class="column cam_menu has-text-centered">
                                    <input type="hidden" name="userId" value="{$user->getUserId()}" id="userId">
                                    <div class="select is-multiple" id="filters_container">
                                        <select name="filters[]" id="filters" multiple>
HTML
    );
    $filters = Filter::getAll();
    foreach ($filters as $filter) {
        $page->appendContent(<<<HTML

                                            <option value="{$filter->getPath()}">{$filter->getName()}</option>
HTML
    );
    }
    $page->appendContent(<<<HTML

                                        </select>
                                    </div>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <div class="has-text-centered">
                                <button class="button is-dark" id="take_photos" type="submit">Take photo</button>
                                <input name="file" class="button is-dark" accept="image/*" id="file_input" type="file">
                                <label for="file_input" class="button is-dark">Upload</label>
                            </div>
                        </div>
                        <div class="column cam_menu has-text-centered">
                            <h1 class="title is-2">Photo list</h1>
                            <div id="photo_list"></div>
                            <br>
                            <button class="button is-dark" id="cancel_photo" type="submit">Cancel</button>
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