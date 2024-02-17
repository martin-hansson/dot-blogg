<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that translates the database values into
 * HTML elements using the components/ library.
 */

declare(strict_types = 1);

require_once dirname(__DIR__) . '/components/Article.php';
require_once dirname(__DIR__) . '/components/Div.php';
require_once dirname(__DIR__) . '/components/Heading.php';
require_once dirname(__DIR__) . '/components/Paragraph.php';
require_once dirname(__DIR__) . '/components/Link.php';
require_once dirname(__DIR__) . '/components/Video.php';
require_once dirname(__DIR__) . '/components/Picture.php';
require_once dirname(__DIR__) . '/components/Source.php';
require_once dirname(__DIR__) . '/components/Image.php';
require_once dirname(__DIR__) . '/components/Button.php';
require_once dirname(__DIR__) . '/components/Span.php';

/**
 * Database model for a post.
 * Used by the database to create an object for each post,
 * Generates an Article for the post with the data filled in.
 */
class Post {
    private int $id;
    private string $title;
    private string $author;
    private DateTime $date;
    private string $content;
    private string $media;
    private Article $post;

    public function __construct($id, $title, $author, $date, $content, $media) {
        $this->id = intval($id);
        $this->title = $title;
        $this->author = $author;
        $this->date = DateTime::createFromFormat('Y-m-d', $date);
        $this->content = $content;
        $this->media = $media;

        // Initialize an empty article tag for the post.
        $this->post = new Article([], [
            'post-id' => $id,
            'class' => 'post'
        ]);

        // Fill the post with content.
        $this->generateContent();
    }

    /**
     * Overrides the __toString() function to render the Article element.
     * 
     * @return Article as HTML.
     */
    public function __toString(): string {
        return $this->post->render();
    }

    /**
     * Helper function that fills the created article element in the format:
     * 
     * <article post-id=$id class="post">
     *   <div class="post-header">
     *     <h3>$author</h3>
     *     <h4>$date</h4>
     *   </div>
     *   <h2>$title</h2>
     *   <p>$content</p>
     *   <picture class="media">
     *     <source srcset="$media" />
     *     <img src="$media" />
     *   </picture
     * </article>
     * 
     * If there is no media link in the database then 
     * the picture / video tag is omitted.
     */
    private function generateContent(): void {
        // Add the author and date of post.
        $postHeader = $this->createPostHeader();
        $this->post->add($postHeader);

        // Add title.
        $this->post->add(new Heading(2, $this->title));

        // Add content and transform links.
        $content = $this->createContent();
        $this->post->add($content);

        // If there is media, add it.
        $media = $this->createMedia();
        if ($media) $this->post->add($media);
    }

    /**
     * Helper function that creates the div 
     * containing the author, date and edit button.
     * Example:
     * <div class="post-header">
     *   <h2>$title</h2>
     *   <h3>$date</h3>
     * </div>
     * 
     * @return Div containing author and date for the post with class post-header.
     */
    private function createPostHeader(): Div {
        $postHeader = new Div([], [
            'class' => 'post-header'
        ]);
        $author = new Heading(3, $this->author);
        $date = new Heading(4, $this->date->format('d/m/Y'), [
            'class' => 'date'
        ]);
        $postHeader->add($author);
        $postHeader->add($date);

        return $postHeader;
    }

    /**
     * Helper function creating a paragraph for the content.
     * If there are links in the content then creates a link tag.
     * Example:
     * <p>$content</p>
     * 
     * @return Paragraph of the content.
     */
    private function createContent(): Paragraph {
        $post = new Paragraph();
        if ($this->hasLink($this->content)) {
            preg_match_all('/https?:\/\/[^\s]+/', $this->content, $links);
            $postParts = preg_split('/https?:\/\/[^\s]+/', $this->content);

            $index = 0;
            foreach ($postParts as $part) {
                $post->add($part);
                if ($index < count($links)) $post->add(new Link($links[$index][0], [
                    'href' => $links[$index][0],
                    'target' => '_blank'
                ]));
                $index++;
            }
        } else {
            $post->add($this->content);
        }

        return $post;
    }

    /**
     * Helper function that creates the necessary tag for the media file
     * if there is any. Creates a picture tag for image files and video
     * tag for video files. Checks the extension to validate the type.
     * Example:
     * <picture>
     *   <source srcset="$media" />
     *   <img src="$media" />
     * </picture>
     * 
     * @return Picture if the media file is an image.
     * @return Video if the media file is a video.
     * @return false if there is no media file.
     */
    private function createMedia(): Picture | Video | bool {
        // If there is an media link, add it to the article.
        if (!empty($this->media)) {
            // Get the extension of the media file.
            $extension = pathinfo($this->media)['extension'];

            if ($extension == 'png' or $extension == 'jpg') {
                // If its an image, return an new Picture tag.
                return new Picture([
                    new Source([
                        'srcset' => $this->media
                    ]),
                    new Image([
                        'src' => $this->media
                    ])
                ], [
                    'class' => 'media'
                ]);
            } else if ($extension == 'mp4' or $extension == 'mov') {
                // If its a video, return a new Video tag.
                return new Video([
                    new Source([
                        'src' => $this->media
                    ])
                ], [
                    'controls' => '', 
                    'class' => 'media'
                ]);
            }
        }

        // If there is no media content, return false.
        return false;
    }

    /**
     * Helper function that checks if there are links in the string.
     * 
     * @param data the string to check for links.
     * @return true if there is a match for a link.
     * @return false if there is no match.
     */
    private function hasLink(string $data): bool {
        return preg_match('/https?:\/\/[^\s]+/', $data) == 1;
    }
}