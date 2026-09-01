<?php
declare(strict_types=1);
require_once __DIR__.'/../youtube/lib.php';

function rfBlogEnsureTables(): void {
    static $ready=false; if($ready)return;
    db()->exec("CREATE TABLE IF NOT EXISTS reddott_blog_videos (
      video_id VARCHAR(32) NOT NULL PRIMARY KEY,
      channel_id VARCHAR(128) NULL,
      title VARCHAR(500) NOT NULL,
      description MEDIUMTEXT NULL,
      published_at DATETIME NULL,
      thumbnail_url TEXT NULL,
      privacy_status VARCHAR(32) NULL,
      source_word_count INT UNSIGNED NOT NULL DEFAULT 0,
      transcript_word_count INT UNSIGNED NOT NULL DEFAULT 0,
      transcript_status VARCHAR(32) NOT NULL DEFAULT 'unknown',
      article_status VARCHAR(32) NOT NULL DEFAULT 'pending',
      indexable TINYINT(1) NOT NULL DEFAULT 0,
      source_updated_at DATETIME NULL,
      created_at DATETIME NOT NULL,
      updated_at DATETIME NOT NULL,
      KEY idx_article_status(article_status), KEY idx_indexable(indexable)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    db()->exec("CREATE TABLE IF NOT EXISTS reddott_blog_transcripts (
      video_id VARCHAR(32) NOT NULL PRIMARY KEY,
      transcript LONGTEXT NULL,
      language_code VARCHAR(32) NULL,
      caption_track_id VARCHAR(128) NULL,
      status VARCHAR(32) NOT NULL DEFAULT 'missing',
      fetched_at DATETIME NULL,
      updated_at DATETIME NOT NULL,
      CONSTRAINT fk_reddott_blog_transcript_video FOREIGN KEY(video_id) REFERENCES reddott_blog_videos(video_id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    db()->exec("CREATE TABLE IF NOT EXISTS reddott_blog_articles (
      video_id VARCHAR(32) NOT NULL PRIMARY KEY,
      headline VARCHAR(500) NOT NULL,
      topic VARCHAR(255) NOT NULL,
      subject VARCHAR(500) NOT NULL,
      intro TEXT NOT NULL,
      source_summary TEXT NULL,
      article_html LONGTEXT NOT NULL,
      keywords TEXT NULL,
      faq_json LONGTEXT NULL,
      word_count INT UNSIGNED NOT NULL DEFAULT 0,
      source_word_count INT UNSIGNED NOT NULL DEFAULT 0,
      indexable TINYINT(1) NOT NULL DEFAULT 0,
      generated_at DATETIME NOT NULL,
      updated_at DATETIME NOT NULL,
      CONSTRAINT fk_reddott_blog_article_video FOREIGN KEY(video_id) REFERENCES reddott_blog_videos(video_id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $ready=true;
}

function rfBlogSaveVideo(string $id,string $channelId,string $title,string $description,string $published,string $thumb,string $privacy,int $sourceWords,int $transcriptWords,string $transcriptStatus,bool $indexable):void{
    rfBlogEnsureTables(); $now=(new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
    $publishedDb=$published!==''?(new DateTimeImmutable($published))->format('Y-m-d H:i:s'):null;
    $q=db()->prepare("INSERT INTO reddott_blog_videos(video_id,channel_id,title,description,published_at,thumbnail_url,privacy_status,source_word_count,transcript_word_count,transcript_status,article_status,indexable,source_updated_at,created_at,updated_at) VALUES(?,?,?,?,?,?,?,?,?,?,?, ?,?,?,?) ON DUPLICATE KEY UPDATE channel_id=VALUES(channel_id),title=VALUES(title),description=VALUES(description),published_at=VALUES(published_at),thumbnail_url=VALUES(thumbnail_url),privacy_status=VALUES(privacy_status),source_word_count=VALUES(source_word_count),transcript_word_count=VALUES(transcript_word_count),transcript_status=VALUES(transcript_status),indexable=VALUES(indexable),source_updated_at=VALUES(source_updated_at),updated_at=VALUES(updated_at)");
    $q->execute([$id,$channelId,$title,$description,$publishedDb,$thumb,$privacy,$sourceWords,$transcriptWords,$transcriptStatus,'pending',$indexable?1:0,$publishedDb,$now,$now]);
}
function rfBlogSaveTranscript(string $id,string $transcript,string $status,string $language='',string $track=''):void{
    rfBlogEnsureTables();$now=(new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
    $q=db()->prepare("INSERT INTO reddott_blog_transcripts(video_id,transcript,language_code,caption_track_id,status,fetched_at,updated_at) VALUES(?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE transcript=VALUES(transcript),language_code=VALUES(language_code),caption_track_id=VALUES(caption_track_id),status=VALUES(status),fetched_at=VALUES(fetched_at),updated_at=VALUES(updated_at)");
    $q->execute([$id,$transcript,$language?:null,$track?:null,$status,$now,$now]);
}
function rfBlogSaveArticle(string $id,array $book,string $articleHtml,bool $indexable):void{
    rfBlogEnsureTables();$now=(new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
    $wc=str_word_count(strip_tags($articleHtml));
    $q=db()->prepare("INSERT INTO reddott_blog_articles(video_id,headline,topic,subject,intro,source_summary,article_html,keywords,faq_json,word_count,source_word_count,indexable,generated_at,updated_at) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE headline=VALUES(headline),topic=VALUES(topic),subject=VALUES(subject),intro=VALUES(intro),source_summary=VALUES(source_summary),article_html=VALUES(article_html),keywords=VALUES(keywords),faq_json=VALUES(faq_json),word_count=VALUES(word_count),source_word_count=VALUES(source_word_count),indexable=VALUES(indexable),generated_at=VALUES(generated_at),updated_at=VALUES(updated_at)");
    $q->execute([$id,$book['headline'],$book['topic'],$book['subject'],$book['intro'],$book['sourceSummary'],$articleHtml,json_encode($book['keywords'],JSON_UNESCAPED_UNICODE),json_encode($book['faq'],JSON_UNESCAPED_UNICODE),$wc,(int)$book['source_word_count'],$indexable?1:0,$now,$now]);
}
