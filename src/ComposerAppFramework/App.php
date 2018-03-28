<?php

namespace Staempfli\Voucher\Library;

class App{

    /** @var Helper */
    var $helper = null;
    /** @var Logger */
    var $logger = null;
    var $config = [];
    /** @var Database */
    var $db = null;
    /** @var Request */
    var $request = null;
    /** @var Assets */
    var $assets = null;
    /** @var Routing */
    var $routing = null;
    /** @var Template */
    var $template = null;
    /** @var Events */
    var $events = null;
    /** @var Model */
    var $model = null;
    /** @var Translation */
    var $translation = null;
    /** @var Session */
    var $session = null;
    /** @var PluginManager */
    var $plugin_manager = null;

    public function __construct($config)
    {
        $this->helper = new Helper();
        $this->logger = new Logger($config, $this->helper);
        $this->assets = new Assets($this);
        $this->events = new Events($this);
        $this->template = new Template($this);
        $this->session = new Session($this);
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return null
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param null $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param null $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return null
     */
    public function getRouting()
    {
        return $this->routing;
    }

    /**
     * @param null $routing
     */
    public function setRouting($routing)
    {
        $this->routing = $routing;
    }

    /**
     * @return null|Helper
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @param null|Helper $helper
     */
    public function setHelper($helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return null|Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param null|Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return null|Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param null|Template $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return null|Assets
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @return Events
     */
    public function getEvents(): Events
    {
        return $this->events;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return null|Translation
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @return null
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return null
     */
    public function getPluginManager()
    {
        return $this->plugin_manager;
    }

    /**
     * @param null $plugin_manager
     */
    public function setPluginManager($plugin_manager)
    {
        $this->plugin_manager = $plugin_manager;
    }

}