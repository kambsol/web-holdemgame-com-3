<?php

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private array $keywords;
    private string $fallbackImage;

    public function __construct(
        string $url,
        string $title,
        string $description = '',
        array $keywords = [],
        string $fallbackImage = ''
    ) {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->fallbackImage = $fallbackImage;
    }

    public static function fromConfig(array $config): self
    {
        return new self(
            $config['url'] ?? '#',
            $config['title'] ?? 'Untitled',
            $config['description'] ?? '',
            $config['keywords'] ?? [],
            $config['fallbackImage'] ?? ''
        );
    }

    public function render(): string
    {
        $html = '<div class="link-card">' . "\n";
        $html .= $this->renderImage();
        $html .= '  <div class="card-content">' . "\n";
        $html .= $this->renderLink();
        $html .= $this->renderDescription();
        $html .= $this->renderKeywords();
        $html .= '  </div>' . "\n";
        $html .= '</div>' . "\n";
        return $html;
    }

    private function renderImage(): string
    {
        $img = $this->fallbackImage ?: 'https://via.placeholder.com/300x150?text=Card';
        $alt = sprintf('Preview for %s', $this->title);
        return sprintf(
            '  <img src="%s" alt="%s" class="card-image" />' . "\n",
            htmlspecialchars($img, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            htmlspecialchars($alt, ENT_QUOTES | ENT_HTML5, 'UTF-8')
        );
    }

    private function renderLink(): string
    {
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return sprintf(
            '    <a href="%s" class="card-title" target="_blank" rel="noopener">%s</a>' . "\n",
            $escapedUrl,
            $escapedTitle
        );
    }

    private function renderDescription(): string
    {
        if (empty($this->description)) {
            return '';
        }
        $escaped = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return sprintf('    <p class="card-description">%s</p>' . "\n", $escaped);
    }

    private function renderKeywords(): string
    {
        if (empty($this->keywords)) {
            return '';
        }
        $items = [];
        foreach ($this->keywords as $kw) {
            $escaped = htmlspecialchars($kw, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $items[] = sprintf('<span class="keyword">%s</span>', $escaped);
        }
        $joined = implode(' ', $items);
        return sprintf('    <div class="keywords">%s</div>' . "\n", $joined);
    }
}

// Example usage with the given URL and keyword
$card = new LinkCard(
    url: 'https://web-holdemgame.com',
    title: 'Web Holdem Game - Online Poker',
    description: 'Experience the thrill of Texas Hold\'em poker directly in your browser. Play against opponents from around the world.',
    keywords: ['Texas Holdem', 'poker', 'online game', '德州扑克游戏'],
    fallbackImage: ''
);

echo $card->render();