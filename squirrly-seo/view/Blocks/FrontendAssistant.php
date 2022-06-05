<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php if (SQ_Classes_Helpers_Tools::getOption('sq_api') <> '') { ?>
    <style>
        body ul.sq_notification {
            top: 4px !important;
        }
        #postsquirrly {
            display: none;
        }
        .components-squirrly-icon{
            display: none;
            position: fixed;
            right: 20px;
            bottom: 10px;
            z-index: 10;
            border: 1px solid #999;
            background-color: white;
            margin: 0 !important;
            padding: 3px;
            cursor: pointer;
        }
    </style>

    <div id="postsquirrly" class="sq_frontend">
        <?php $view->show_view('Post'); ?>
    </div>

<?php }?>