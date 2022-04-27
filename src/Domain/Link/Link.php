<?php

namespace App\Domain\Link;

use App\Domain\DomainAbstraction;
use App\Helpers\Authenticated;
use App\Helpers\Functions;
use PDO;

class Link extends DomainAbstraction implements LinkRepository
{
    protected $base = '';

    public function __construct()
    {
        parent::__construct();
        $this->base = $_ENV['APPLICATION_URL'] . '/go/';
    }

    /**
     * @param string $link
     * @return string
     */
    public function create(string $link): string
    {
        // Check if link already exists
        $linkExist = $this->linkExists($link);
        if($linkExist) {
            // Return link
            return $this->base . $linkExist[0]['short'];
        }

        // Create new link
        $linkModel = $this->db->prepare('INSERT INTO `links` (user_id, link, short) VALUES (:user_id, :link, :short)');
        
        // generate short link
        $short = Functions::shortLink($link);

        // execute query and bind parameters
        $linkModel->execute([
            'user_id' => Authenticated::user()['user_id'],
            'link' => $link,
            'short' => $short
        ]);

        return $this->base . $short;
    }

    /**
     * @return array
     */
    public function list(): array
    {
        // get list of links from database or cache
        $results = $this->cache->remember('link:list', function () {
            // Get links
            $linkModel = $this->db->prepare('SELECT * FROM links WHERE user_id = :user_id');

            // execute query and bind parameters
            $linkModel->execute([
                'user_id' => Authenticated::user()['user_id']
            ]);
            
            // fetch all links
            return $linkModel->fetchAll(PDO::FETCH_ASSOC);
        });

        // if has values return links
        if($results) {
            return $results;
        }
        
        // throw exception
        throw new \Exception('No links found.');
    }

    /**
     * @param int $id
     * @return array
     */
    public function view(int $id): array
    {
        // get list of links from database or cache
        $results = $this->cache->remember('link:view:'.$id, function () use ($id) {
            // Get link
            $linkModel = $this->db->prepare('SELECT * FROM links WHERE id = :id AND user_id = :user_id');

            // execute query and bind parameters
            $linkModel->execute([
                'id' => $id,
                'user_id' => Authenticated::user()['user_id']
            ]);
            
            // fetch link
            return $linkModel->fetch(PDO::FETCH_ASSOC);
        });
        
        // if has values return link
        if($results) {
            return $results;
        }

        // throw exception
        throw new \Exception('Link not found.');
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        // delete link
        $linkModel = $this->db->prepare('DELETE FROM links WHERE id = :id and user_id = :user_id');

        // execute query and bind parameters
        $linkModel->execute([
            'id' => $id,
            'user_id' => Authenticated::user()['user_id']
        ]);

        // if no rows were affected, throw an exception
        if($linkModel->rowCount() == 0) {
            throw new \Exception('Link not found.');
        }

        // clear cache
        $this->clearCache($id);

    }

    /**
     * @param int $id
     * @param string $link
     * @return string
     */
    public function update(int $id, string $link): string
    {
        // Check if link already exists
        if($this->linkExists($link)) {
            throw new \Exception('Link already exists.');
        }

        // generate short link
        $short = Functions::shortLink($link);

        // update link
        $linkModel = $this->db->prepare('UPDATE links SET link = :link , short = :short WHERE id = :id and user_id = :user_id');

        // execute query and bind parameters
        $linkModel->execute([
            'link' => $link,
            'short' => $short,
            'id' => $id,
            'user_id' => Authenticated::user()['user_id']
        ]);

        // if no rows were affected, throw an exception
        if($linkModel->rowCount() == 0) {
            throw new \Exception('Link not found.');
        }

        // clear cache
        $this->clearCache($id);

        return $this->base . $short;
    }

    /**
     * @param string $short
     * @return string
     */
    public function gotoLink(string $short): string
    {
        // Get link by short
        $linkModel = $this->db->prepare('SELECT * FROM links WHERE short = :short');

        // execute query and bind parameters
        $linkModel->execute([
            'short' => $short
        ]);

        // fetch link
        $results = $linkModel->fetch(PDO::FETCH_ASSOC);

        // if has values return link
        if($results) {
            return $results['link'];
        }

        // throw exception
        throw new \Exception('Link not found.');
    }

    /**
     * @param string $link
     * @return array
     */
    private function linkExists(string $link): array
    {
        // Get link 
        $linkModel = $this->db->prepare('SELECT * FROM links WHERE link = :link AND user_id = :user_id');

        // execute query and bind parameters
        $linkModel->execute([
            'link' => $link,
            'user_id' => Authenticated::user()['user_id']
        ]);

        // fetch link
        return $linkModel->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     */
    private function clearCache($id)
    {
        $this->cache->delete('link:list');
        $this->cache->delete('link:view:'.$id);
    }
}