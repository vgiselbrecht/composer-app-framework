<?php
/**
 * Singelton
 *
 * @copyright Copyright © 2018 Staempfli AG. All rights reserved.
 * @author    valentin.giselbrecht@staempfli.at
 */

namespace ComposerAppFramework;


class Singelton
{
    /** @var App */
    var $app = null;

    public function __construct($app)
    {
        $this->app = $app;
    }

}