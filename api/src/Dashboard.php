<?php

class Dashboard
{
    public array $params;

    public function __construct()
    {
        $this->params = [];
        $url_components = parse_url($_SERVER["REQUEST_URI"]);
        if (isset($url_components['query'])) {
            parse_str($url_components['query'], $this->params);
        }
    }

    public function processRequest(string $method = "GET"): void
    {
        if (!array_key_exists("key", $this->params) || empty($this->params["key"])) {
            ResponseMessages::unauthorized();
            return;
        }

        $key = $this->params["key"];

        if ($method !== "GET") {
            ResponseMessages::methodNotAllowed("GET");
            return;
        }


        $totalClicksData = ShortUrlGateway::getClicks($key);
        $urlsData = ShortUrlGateway::getAll($key);
        $startDate = date('m-01-Y');
        $endDate = date('m-t-Y');
        $linksThisMonth = ShortUrlGateway::getLinksByDateRange($startDate, $endDate, $key);
        $recentLinks = ShortUrlGateway::getRecentLinks($key);

        $modifiedLinks = array_map(function ($recentLink) {
            $recentLink["ShortUrl"] = BASE_URL . $recentLink["UniqueIdentifier"];
            return $recentLink;
        }, $recentLinks);

        $data = [
            "TotalLinks" => count($urlsData),
            "TotalClicks" => (int) $totalClicksData["TotalClicks"],
            "LinksThisMonth" => count($linksThisMonth),
            "RecentLinks" => $modifiedLinks
        ];

        ResponseMessages::showResult($data, true);
    }
}
