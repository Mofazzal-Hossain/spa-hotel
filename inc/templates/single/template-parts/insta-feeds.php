<?php
// Don't load directly
defined('ABSPATH') || exit;

$instagramAccountId = '17841405657898548'; 
$accessToken = 'YOUR_ACCESS_TOKEN'; // long-lived token

// Function to fetch posts
function fetchInstagramPosts($accountId, $accessToken) {
    $endpoint = "https://graph.facebook.com/v12.0/{$accountId}?fields=business_discovery.username(mofazzalhossain40){media{id,caption,like_count,comments_count,media_type,media_url,permalink,timestamp}}&access_token={$accessToken}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Fetch your posts
$instagramData = fetchInstagramPosts($instagramAccountId, $accessToken);

$posts = $instagramData['business_discovery']['media']['data'] ?? [];
?>

<div class="tf-insta-feeds spa-single-section">
    <h4>Guest Experiences</h4>
    <?php foreach($posts as $post): ?>
        <div class="post">
            <?php if($post['media_type'] == 'IMAGE' || $post['media_type'] == 'CAROUSEL_ALBUM'): ?>
                <img src="<?php echo $post['media_url']; ?>" alt="Instagram Image">
            <?php elseif($post['media_type'] == 'VIDEO'): ?>
                <video controls>
                    <source src="<?php echo $post['media_url']; ?>" type="video/mp4">
                </video>
            <?php endif; ?>
            <p><b>Likes:</b> <?php echo $post['like_count']; ?></p>
            <p><b>Comments:</b> <?php echo $post['comments_count']; ?></p>
            <p><b>Caption:</b> <?php echo nl2br($post['caption']); ?></p>
            <p><b>Posted at:</b> <?php echo $post['timestamp']; ?></p>
            <p><a href="<?php echo $post['permalink']; ?>" target="_blank">View on Instagram</a></p>
        </div>
    <?php endforeach; ?>

</div>