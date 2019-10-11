<?php

namespace csps\sapbot\services\apis;

class SynonymApi extends BaseApi
{
    protected function baseUrl()
    {
        return "train/v2/users/{$this->userSlug}/bots/{$this->botSlug}/versions/{$this->botVersion}/dataset/gazettes";
    }

    public function getAll(string $gazetteSlug, string $language = 'en')
    {
        return $this->client->get("{$this->baseUrl()}/$gazetteSlug/synonyms", ['language' => $language])->synonyms;
    }

    public function get(string $gazetteSlug, string $synonymSlug, string $language = 'en')
    {
        return $this->client->get("{$this->baseUrl()}/$gazetteSlug/synonyms/$synonymSlug", ['language' => $language])->synonyms;
    }

    public function create(string $gazetteSlug, string $synonym, string $language = 'en')
    {
        return $this->client->post("{$this->baseUrl()}/$gazetteSlug/synonyms", [
            'value'    => $synonym,
            'language' => $language,
        ]);
    }

    public function update(string $gazetteSlug, string $synonymSlug, string $synonym, string $language = 'en')
    {
        return $this->client->put("{$this->baseUrl()}/$gazetteSlug/synonyms/$synonymSlug", [
            'value'    => $synonym,
            'language' => $language,
        ]);
    }

    public function delete(string $gazetteSlug, string $synonymSlug)
    {
        return $this->client->delete("{$this->baseUrl()}/$gazetteSlug/synonyms/$synonymSlug");
    }
}
