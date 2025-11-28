# Scheduled Content Publishing Setup

FiltCMS provides two methods to automatically publish scheduled pages and blogs:

## Method 1: Laravel Scheduler (Recommended)

### Setup

1. **Enable Laravel Scheduler**

Add this cron entry to your server (runs every minute):

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

The scheduled task will check every minute and publish any content where `status='scheduled'` and `published_at <= now()`.

### Manual Test

You can manually trigger the scheduled task:

```bash
php artisan filtcms:publish-scheduled
```

This will output the number of pages and blogs published.

### Verify Scheduler

Check if your scheduled tasks are configured correctly:

```bash
php artisan schedule:list
```

You should see `filtcms:publish-scheduled` listed with "Every minute" frequency.

---

## Method 2: URL Trigger

You can trigger content publishing by calling a URL endpoint. This is useful for:
- External cron services (like cron-job.org, EasyCron)
- Webhook integrations
- Manual triggers
- Services without shell access

### Setup

1. **Generate a Security Token (Optional but Recommended)**

```bash
php artisan tinker --execute="echo Str::random(32)"
```

2. **Add Token to `.env` File**

```env
FILTCMS_PUBLISH_TOKEN=your-generated-token-here
```

If you skip this step, the endpoint will work without authentication (not recommended for production).

### Usage

**Without Token:**
```bash
# GET request
curl https://yourdomain.com/publish-scheduled-content

# Or simply visit in browser
https://yourdomain.com/publish-scheduled-content
```

**With Token (Header):**
```bash
curl -H "X-Publish-Token: your-token-here" \
	 https://yourdomain.com/publish-scheduled-content
```

**With Token (Query Parameter):**
```bash
curl https://yourdomain.com/publish-scheduled-content?token=your-token-here

# Or in browser
https://yourdomain.com/publish-scheduled-content?token=your-token-here
```

### Response Format

Success response:
```json
{
	"success": true,
	"message": "Scheduled content published successfully",
	"published": {
		"pages": 2,
		"blogs": 5,
		"total": 7
	},
	"timestamp": "2025-11-27T10:30:00.000000Z"
}
```

Error response (invalid token):
```json
{
	"success": false,
	"message": "Unauthorized"
}
```

### External Cron Services

**cron-job.org:**
1. Create a free account at https://cron-job.org
2. Add a new cron job
3. Set URL: `https://yourdomain.com/publish-scheduled-content?token=your-token`
4. Set schedule: Every 1 minute (or as needed)
5. Add custom header if using header authentication: `X-Publish-Token: your-token`

**Cronitor:**
1. Create an account at https://cronitor.io
2. Add a new monitor — choose an HTTP/URL check
3. Set URL: `https://yourdomain.com/publish-scheduled-content?token=your-token`
4. Set schedule: Every 1 minute (or as needed)
5. Add request header for header-based auth (Request Headers / Advanced): `X-Publish-Token: your-token`
6. Save and enable the monitor and alerts as required

**EasyCron:**
1. Create account at https://www.easycron.com
2. Add new cron job
3. URL: `https://yourdomain.com/publish-scheduled-content?token=your-token`
4. Cron Expression: `* * * * *` (every minute)

**Other webhook services:**
- Zapier
- IFTTT
- Make (formerly Integromat)
- Any service that can make HTTP GET requests

---

## How It Works

1. **Pages and Blogs** with `status='scheduled'` and `published_at` set to a future date will not appear on the frontend
2. When the scheduled time arrives, the cron job (or URL trigger) checks for eligible content
3. Content is automatically changed from `status='scheduled'` to `status='published'`
4. Published content immediately becomes visible on the website

---

## Troubleshooting

### Scheduler Not Running

Check if your cron job is set up correctly:
```bash
crontab -l
```

Manually test the scheduler:
```bash
php artisan schedule:run
```

### URL Trigger Not Working

1. Verify the route is accessible:
```bash
php artisan route:list | grep publish-scheduled
```

2. Check if token is set correctly in `.env`

3. Test without token (temporarily remove `publish_token` from config):
```bash
curl -v https://yourdomain.com/publish-scheduled-content
```

4. Check server logs:
```bash
tail -f storage/logs/laravel.log
```

### Content Not Publishing

Check if content meets the criteria:
```bash
php artisan tinker
>>> \EthickS\FiltCMS\Models\Blog::where('status', 'scheduled')->where('published_at', '<=', now())->count()
>>> \EthickS\FiltCMS\Models\Page::where('status', 'scheduled')->where('published_at', '<=', now())->count()
```

---

## Security Recommendations

1. **Always use a token in production** - Add `FILTCMS_PUBLISH_TOKEN` to your `.env` file
2. **Use HTTPS** - Ensure your site uses SSL/TLS
3. **Monitor logs** - Check for unauthorized access attempts
4. **Rate limiting** - Consider adding rate limiting to the URL endpoint if needed
5. **IP whitelisting** - Restrict access to known IP addresses in production

---

## Comparison: Scheduler vs URL Trigger

| Feature               | Laravel Scheduler      | URL Trigger                |
| --------------------- | ---------------------- | -------------------------- |
| Requires shell access | ✅ Yes                  | ❌ No                      |
| Setup complexity      | Medium                 | Easy                       |
| Security              | High (internal)        | Medium (requires token)    |
| External monitoring   | No                     | Yes (via uptime monitors)  |
| Webhook integration   | No                     | Yes                        |
| Runs automatically    | ✅ Yes                  | Only when triggered        |
| Best for              | VPS, Dedicated Servers | Shared Hosting, Serverless |

---

## Testing

Create a test blog/page with scheduled status:
```bash
php artisan tinker
```

```php
// Create a scheduled blog
$blog = \EthickS\FiltCMS\Models\Blog::create([
	'title' => 'Test Scheduled Post',
	'slug' => 'test-scheduled-post',
	'body' => 'This is a test',
	'status' => 'scheduled',
	'published_at' => now()->addMinutes(2),
	'user_id' => 1,
]);

// Wait 2 minutes, then trigger publishing
// Method 1: Run command
php artisan filtcms:publish-scheduled

// Method 2: Call URL
curl https://yourdomain.com/publish-scheduled-content?token=your-token

// Check if published
$blog->fresh()->status; // Should be 'published'
```
