<?php
// Don't load directly
defined('ABSPATH') || exit;


$hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);
$sec_title = ! empty($hotel_meta['feeds-sec-title']) ? $hotel_meta['feeds-sec-title'] : esc_html__('Guest Experiences', 'spa-hotel-toolkit');
$accountId = ! empty($hotel_meta['feeds-account-id']) ? $hotel_meta['feeds-account-id'] : '';
$accessToken = ! empty($hotel_meta['feeds-access-token']) ? $hotel_meta['feeds-access-token'] : '';

// Function to fetch posts
function fetchInstagramPosts($accountId, $accessToken)
{
    global $post_id;
    $hotel_meta = get_post_meta($post_id, 'tf_hotels_opt', true);
    $username = ! empty($hotel_meta['feeds-username']) ? $hotel_meta['feeds-username'] : '';
    $endpoint = "https://graph.facebook.com/v12.0/{$accountId}?fields=business_discovery.username({$username}){media{id,caption,like_count,comments_count,media_type,media_url,permalink,timestamp}}&access_token={$accessToken}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Fetch your posts
$instagramData = fetchInstagramPosts($accountId, $accessToken);

$posts = $instagramData['business_discovery']['media']['data'] ?? [];
?>

<div class="tf-insta-feeds spa-single-section">
    <?php if(!empty($sec_title)): ?>
        <h4 class="tf-section-title">
            <?php echo esc_html($sec_title); ?>
        </h4>
    <?php endif; ?>
    <!-- </?php foreach($posts as $post): ?>
        <div class="post">
            </?php if($post['media_type'] == 'IMAGE' || $post['media_type'] == 'CAROUSEL_ALBUM'): ?>
                <img src="</?php echo $post['media_url']; ?>" alt="Instagram Image">
            </?php elseif($post['media_type'] == 'VIDEO'): ?>
                <video controls>
                    <source src="</?php echo $post['media_url']; ?>" type="video/mp4">
                </video>
            </?php endif; ?>
            <p><b>Likes:</b> </?php echo $post['like_count']; ?></p>
            <p><b>Comments:</b> </?php echo $post['comments_count']; ?></p>
            <p><b>Caption:</b> </?php echo nl2br($post['caption']); ?></p>
            <p><b>Posted at:</b> </?php echo $post['timestamp']; ?></p>
            <p><a href="</?php echo $post['permalink']; ?>" target="_blank">View on Instagram</a></p>
        </div>
    </?php endforeach; ?> -->

    <div class="tf-insta-post-wrap">
        <?php for ( $i = 0; $i < 7; $i++ ): ?> 
            <div class="tf-insta-post">
                <div class="tf-insta-post-top">
                    <div class="insta-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <g clip-path="url(#clip0_535_6431)">
                                <path d="M14.5834 5.41602H14.5917M5.83341 1.66602H14.1667C16.4679 1.66602 18.3334 3.5315 18.3334 5.83268V14.166C18.3334 16.4672 16.4679 18.3327 14.1667 18.3327H5.83341C3.53223 18.3327 1.66675 16.4672 1.66675 14.166V5.83268C1.66675 3.5315 3.53223 1.66602 5.83341 1.66602ZM13.3334 9.47435C13.4363 10.1679 13.3178 10.8762 12.9949 11.4985C12.672 12.1209 12.161 12.6255 11.5348 12.9407C10.9085 13.256 10.1988 13.3657 9.50657 13.2543C8.81435 13.1429 8.17488 12.8161 7.67911 12.3203C7.18335 11.8245 6.85652 11.1851 6.74514 10.4929C6.63375 9.80064 6.74347 9.09093 7.05869 8.46466C7.3739 7.83839 7.87857 7.32747 8.5009 7.00455C9.12323 6.68163 9.83154 6.56317 10.5251 6.66602C11.2325 6.77092 11.8875 7.10057 12.3932 7.60627C12.8989 8.11197 13.2285 8.76691 13.3334 9.47435Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_535_6431">
                                    <rect width="20" height="20" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <img class="tf-insta-feed-image" src="<?php echo esc_url(SHT_HOTEL_TOOLKIT_ASSETS . 'images/insta-feed-image.png'); ?>" alt="Instagram Image">
                    <div class="insta-user-info">
                        <img src="<?php echo esc_url(SHT_HOTEL_TOOLKIT_ASSETS . 'images/insta-user.png'); ?>" alt="Avatar">
                        <a href="#"><?php echo esc_html__('@guest_user1', 'spa-hotel-toolkit'); ?></a>
                    </div>
                </div>
                <div class="tf-insta-post-bottom">
                    <a href="#" class="tf-post-title"><?php echo esc_html__('Sauna Room', 'spa-hotel-toolkit'); ?></a>
                    <div class="tf-post-meta">
                        <ul>
                            <li>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M1.66675 7.91711C1.66677 6.98977 1.94808 6.08426 2.47353 5.32016C2.99898 4.55606 3.74385 3.96932 4.60976 3.63744C5.47567 3.30556 6.42188 3.24414 7.32343 3.46131C8.22497 3.67848 9.03944 4.16401 9.65925 4.85377C9.7029 4.90045 9.75568 4.93767 9.81431 4.96311C9.87294 4.98855 9.93617 5.00168 10.0001 5.00168C10.064 5.00168 10.1272 4.98855 10.1859 4.96311C10.2445 4.93767 10.2973 4.90045 10.3409 4.85377C10.9588 4.15952 11.7734 3.66991 12.6764 3.45011C13.5795 3.2303 14.528 3.29073 15.3958 3.62334C16.2636 3.95596 17.0096 4.54498 17.5343 5.31203C18.0591 6.07907 18.3378 6.98774 18.3334 7.91711C18.3334 9.82544 17.0834 11.2504 15.8334 12.5004L11.2567 16.9279C11.1015 17.1063 10.91 17.2495 10.6951 17.3482C10.4802 17.4468 10.2468 17.4986 10.0103 17.5001C9.77386 17.5016 9.53979 17.4528 9.32365 17.3569C9.10752 17.261 8.91427 17.1201 8.75675 16.9438L4.16675 12.5004C2.91675 11.2504 1.66675 9.83377 1.66675 7.91711Z" stroke="#7C7C7C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <span><?php echo esc_html__('180', 'spa-hotel-toolkit'); ?></span>
                            </li>
                            <li>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <g clip-path="url(#clip0_535_6442)">
                                            <path d="M6.66673 9.99909H6.67507M10.0001 9.99909H10.0084M13.3334 9.99909H13.3417M2.4934 13.6174C2.61593 13.9265 2.64321 14.2652 2.57173 14.5899L1.68423 17.3316C1.65564 17.4706 1.66303 17.6147 1.70571 17.75C1.7484 17.8854 1.82495 18.0077 1.92812 18.1052C2.03129 18.2027 2.15766 18.2722 2.29523 18.3071C2.43281 18.3421 2.57704 18.3413 2.71423 18.3049L5.5584 17.4733C5.86483 17.4125 6.18218 17.439 6.47423 17.5499C8.25372 18.3809 10.2695 18.5568 12.166 18.0464C14.0625 17.536 15.7178 16.3721 16.8398 14.7602C17.9618 13.1483 18.4785 11.1919 18.2986 9.23622C18.1188 7.2805 17.254 5.45115 15.8568 4.07092C14.4596 2.6907 12.6198 1.84829 10.6621 1.69234C8.70429 1.53639 6.75435 2.07691 5.15627 3.21854C3.55819 4.36017 2.41468 6.02955 1.92748 7.93212C1.44028 9.8347 1.64071 11.8482 2.4934 13.6174Z" stroke="#7C7C7C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_535_6442">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <span><?php echo esc_html__('12', 'spa-hotel-toolkit'); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endfor;?>
    </div>

</div>