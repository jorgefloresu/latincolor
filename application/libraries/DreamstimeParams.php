<?php

/**
 * Constants for predefined Dreamstime API commands and parameters.
 *
 *
 */
class DreamstimeParams
{
    const API_CMD           = 'api.xml?';
    const API_KEY           = 'username';
    const API_PASSWORD      = 'password';
    const TYPE_NAME         = 'type';
    const TYPE_CMD          = 'get';
    const REQUEST_NAME      = 'request';
    const SEARCH_CMD        = 'search';
    const SEARCH_QUERY      = 'srh_field';
    const SEARCH_LIMIT      = 'ipp';
    const SEARCH_PAGE       = 'pg';
    const SEARCH_ORIENTATION= 'orientation';
    const SEARCH_COLOR      = 'color';
    const SEARCH_PHOTO      = 'photo';
    const SEARCH_VIDEO      = 'video';
    const SEARCH_VECTOR     = 'additional';
    const SEARCH_LEVEL      = 'level';

    const MEDIA_DATA_CMD     = 'image';
    const MEDIA_IMAGE_CODE   = 'imageID';
    const MEDIA_VIDEO_CMD    = 'video';
    const MEDIA_VIDEO_CODE   = 'videoID';
    const MEDIA_SIZE         = 'size';
    const MEDIA_COPIES       = 'copies';

    const DOWNLOAD_CMD       = 'download';
}

?>
