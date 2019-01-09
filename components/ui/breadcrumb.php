<?php
class breadcrumb {

  public function __construct() {
    if (!is_single())
      return;
  }

  private function get_breadcrumb_data() {
    global $post;
    $post_title = $post->post_title;
    $post_url = get_the_permalink( $post->ID );
    $post_img = get_the_post_thumbnail_url($post);

    $categories = get_the_category($post->ID);
    $cat_count = count($categories);
    $parent_cat = '';
    $parent_cat_url = '';
    $child_cat = '';
    $child_cat_url = '';

    for ($i = 0; $i < $cat_count; $i++) {
      if(empty($parent_cat) && $categories[$i]->category_parent == 0) {
        $parent_cat = $categories[$i]->name;
        $parent_cat_url = get_category_link($categories[$i]->term_id);
      }
      elseif (empty($child_cat)) {
        $child_cat = $categories[$i]->name;
        $child_cat_url = get_category_link($categories[$i]->term_id);
      }
    }

    return array('post_title' => $post_title, 'post_url' => $post_url, 'post_img' => $post_img, 'parent_cat' => $parent_cat, 'parent_cat_url' => $parent_cat_url, 'child_cat' => $child_cat, 'child_cat_url' => $child_cat_url);
  }

  //breadcrumb schema json-ld
  public function insert_breadcrumb_schema() {

    if (!is_single())
      return;

    $breadcrumb_data = $this->get_breadcrumb_data();

    $count = 0;
    function counter(&$count) {
      $count++;
      return $count;
    }

    $parent_cat_crumb =
    (!empty($breadcrumb_data['parent_cat'])) ?
    '{
      "@type": "ListItem",
      "position": '.counter($count).',
      "item":
      {
      "@id": "'.$breadcrumb_data['parent_cat_url'].'",
      "name": " ' . $breadcrumb_data['parent_cat'] . ' "
      }
    },':'';

    $child_cat_crumb =
    (!empty($breadcrumb_data['child_cat'])) ?
    '{
      "@type": "ListItem",
      "position": '.counter($count).',
      "item":
      {
      "@id": "'.$breadcrumb_data['child_cat_url'].'",
      "name": "'.$breadcrumb_data['child_cat'].'"
      }
    },':'';

    $post_crumb =
    (!empty($breadcrumb_data['post_title'])) ?
    '{
      "@type": "ListItem",
      "position": '.counter($count).',
      "item":
      {
        "@id": "'.$breadcrumb_data['post_url'].'",
        "name": "'.$breadcrumb_data['post_title'].'",
        "image": "'.$breadcrumb_data['post_img'].'"
      }
    }':'';


    $json_crumbs =
    '{
       "@context": "http://schema.org",
       "@type": "BreadcrumbList",
       "itemListElement":
       [
         '. $parent_cat_crumb . $child_cat_crumb . $post_crumb .'
       ]
     }';


    return $json_crumbs;
  }

  //breadcrumb print function
  public function get_the_breadcrumb() {

    $breadcrumb_data = $this->get_breadcrumb_data();

    if (!is_single())
      return;

    $parent_node = (!empty($breadcrumb_data['parent_cat'])) ? '<li class="breadcrumbs_li"><a class="breadcrumbs_a" href="'.$breadcrumb_data['parent_cat_url'].'">'.$breadcrumb_data['parent_cat'].'</a><span class="breadcrumbs_spacer">/</span></li>':'';

    $child_node = (!empty($breadcrumb_data['child_cat'])) ? '<li class="breadcrumbs_li"><a class="breadcrumbs_a" href="'.$breadcrumb_data['child_cat_url'].'">'.$breadcrumb_data['child_cat'].'</a><span class="breadcrumbs_spacer">/</span></li>':'';

    $post_node = (!empty($breadcrumb_data['post_title'])) ? '<li class="breadcrumbs_li breadcrumbs_post"><a class="breadcrumbs_a" href="'.$breadcrumb_data['post_url'].'">'.$breadcrumb_data['post_title'].'</a></li>':'';

    $breadcrumbs =
    '<ul class="breadcrumbs">'
      .
        $parent_node . $child_node . $post_node
      .
    '</ul>';

    return $breadcrumbs;
  }

}
