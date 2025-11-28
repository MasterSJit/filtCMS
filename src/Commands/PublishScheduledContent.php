<?php

namespace EthickS\FiltCMS\Commands;

use EthickS\FiltCMS\Models\Blog;
use EthickS\FiltCMS\Models\Page;
use Illuminate\Console\Command;

class PublishScheduledContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filtcms:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled blogs and pages when their publish time arrives';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for scheduled content to publish...');

        $publishedPagesCount = $this->publishScheduledPages();
        $publishedBlogsCount = $this->publishScheduledBlogs();

        $totalPublished = $publishedPagesCount + $publishedBlogsCount;

        if ($totalPublished > 0) {
            $this->info("Successfully published {$publishedPagesCount} page(s) and {$publishedBlogsCount} blog(s).");
        } else {
            $this->info('No scheduled content ready to publish.');
        }

        return Command::SUCCESS;
    }

    /**
     * Publish scheduled pages that are ready.
     */
    protected function publishScheduledPages(): int
    {
        $pages = Page::query()
            ->where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->get();

        foreach ($pages as $page) {
            $page->update([
                'status' => 'published',
            ]);

            $this->line("  ✓ Published page: {$page->title}");
        }

        return $pages->count();
    }

    /**
     * Publish scheduled blogs that are ready.
     */
    protected function publishScheduledBlogs(): int
    {
        $blogs = Blog::query()
            ->where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->get();

        foreach ($blogs as $blog) {
            $blog->update([
                'status' => 'published',
            ]);

            $this->line("  ✓ Published blog: {$blog->title}");
        }

        return $blogs->count();
    }
}
