<?php

/**
 * Constants for predefined Fotolia API commands and parameters.
 * 
 * 
 */
class AdobeStockParams
{
    const SEARCH_CMD         = 'Search/Files?';
    const SEARCH_QUERY       = 'search_parameters[words]';
    const SEARCH_LIMIT       = 'search_parameters[limit]';
    const SEARCH_OFFSET      = 'search_parameters[offset]';
    const THUMB_SIZE		 = 'search_parameters[thumbnail_size]';
    const SEARCH_ORIENTATION = 'search_parameters[filters][orientation]';
    const SEARCH_COLOR       = 'search_parameters[filters][colors]';
    const TYPE_PHOTO         = 'search_parameters[filters][content_type:photo]';
    const TYPE_VIDEO         = 'search_parameters[filters][content_type:video]';
    const SIMILAR_IMAGE      = 'search_parameters[similar_image]';
    const MEDIA_ID           = 'search_parameters[media_id]';

    const RESULT_NUM_ROWS    = 'result_columns[]=nb_results';
    const RESULT_ID          = 'result_columns[]=id';
    const RESULT_TITLE       = 'result_columns[]=title';
    const RESULT_KEYWORDS    = 'result_columns[]=keywords';
    const RESULT_THUMBNAIL   = 'result_columns[]=thumbnail_url';
    const RESULT_TYPE_ID     = 'result_columns[]=media_type_id';
    const RESULT_THUMB_1000   = 'result_columns[]=thumbnail_1000_url';
    const RESULT_VIDEO_PREVIEW   = 'result_columns[]=video_preview_url';
    const RESULT_COMPS       = 'result_columns[]=comps';

    const MEDIA_DATA_CMD     = 'Search/Files?';

}

?>