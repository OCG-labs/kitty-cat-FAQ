<?php

namespace oneTheme;

class Selectable_FAQ extends OneModule{

  public function init() {
      $this->custom_post_type();
      $this->custom_post_tax();
      add_shortcode('sectioned-faq', array($this, 'faq_sc_view'));
  }

  public function custom_post_type() {
      $faq = new \Super_Custom_Post_Type( 'faqlist', 'FAQ', 'Frequently Asked Questions');
      $this->$faq->add_to_columns( 'faq-cat' );
      $this->$faq->set_icon( 'list-alt' );
  }

  public function custom_post_tax() {
      $faqTax = new \Super_Custom_Taxonomy( 'faq-cat', 'FAQ Category', 'FAQ Categories', 'cat' );
      \connect_types_and_taxes( $this->$faq, $this->$faqTax );

  }

  public function faq_sc_view() {
      ob_start();

      $terms = \get_terms($tax, 'orderby=slug&hide_empty=1');
      	echo '<ul class="list-inline text-center">';
      if(!empty($terms)) : foreach($terms as $tterm) :

      	echo '<li><a class="smoothScroll btn btn-dark" href="#'.$tterm->slug.'">'.$tterm->name.'</a></li>';

      	endforeach;
      	echo '</ul>';
      endif;

      if(!empty($terms)) : foreach($terms as $term) :

      		$term_name = $term->slug;

      		$args = array(
      			'post_type' => 'faqlist',
      			'tax_query' => array(
      				array(
      					'taxonomy' => 'faq-cat',
      					'field' => 'slug',
      					'terms' => $term_name
      				)
      			),
      			'orderby' => 'menu_order',
      			'order' => 'ASC',
      			'posts_per_page' => -1
      		);

      		$terms_posts = new \WP_Query($args);


      		echo '<div class="col-md-12"><h2 class="tan" id="'.$term->slug.'">'.$term->name.'</h2></div>';

      		$i = 0;
      		echo '<div class="col-md-12 clearfix" style="padding-bottom: 25px;">';
      		echo '<div class="panel-group" id="accordion'.$term->slug.'">';

      		if($terms_posts->have_posts()) : while ($terms_posts->have_posts()) : $terms_posts->the_post();
      				echo '<div class="panel panel-default">';
      			    echo '<div class="panel-heading clearfix">';
      			    echo '<h4 class="panel-title">';
      			    echo '<a data-toggle="collapse" data-parent="#accordion'.$term->slug.'" href="#collapse'.$i.$term->term_id.'">';
      			    the_title();
      			    echo '</a>';
      			    echo '</h4>';
      			    echo '</div>';
      			    echo '<div id="collapse'.$i.$term->term_id.'" class="panel-collapse collapse">';
      			    echo '<div class="panel-body">';
      				the_content();
      				echo '</div>';
      				echo '</div>';
      				echo '</div>';
      				$i++;

      			endwhile;
      			echo '</div>';
      			echo '</div>';
      		endif;

      	endforeach;
      endif;

      $content = ob_get_contents();
      		ob_end_clean();
      		return $content;
  }


}

new Selectable_FAQ();
