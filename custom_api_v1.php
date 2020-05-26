<?php 
 
   /*
  Plugin Name: Home market REST API
  Description: Plugin de gestion de location immobilière.
  Author: Equima
  Version: 1.0.0
 */
 
 class Home_market_Rest_api{

  public function __construct()
  {
   
     // Register the rest route here.
 
     add_action( 'rest_api_init', function () {
            
            register_rest_route( 'mynamespace/v1', 'test',array(
 
                'methods'  => 'GET',
                'callback' => array($this, 'get_latest_post'),
 
            ) );

              register_rest_route( 'mynamespace/v1', 'propertys',array(
 
                'methods'  => 'GET',
                'callback' => array($this, 'get_property'),
 
            ) );

                register_rest_route( 'mynamespace/v1', 'property/(?P<id>\d+)',array(
 
                'methods'  => 'GET',
                'callback' => array($this, 'get_one_property'),
 
            ) );

                register_rest_route( 'mynamespace/v1', 'agents',array(
 
                'methods'  => 'GET',
                'callback' => array($this, 'get_agents'),
 
            ) );

                register_rest_route( 'mynamespace/v1', 'agent/(?P<id>\d+)',array(
 
                'methods'  => 'GET',
                'callback' => array($this, 'get_one_agent'),
 
            ) );

              register_rest_route( 'mynamespace/v1', 'categorys',array(
   
                  'methods'  => 'GET',
                  'callback' => array($this, 'get_categorys'),
   
              ) );

               register_rest_route( 'mynamespace/v1', 'status',array(
   
                  'methods'  => 'GET',
                  'callback' => array($this, 'get_status'),
   
              ) );

              register_rest_route( 'mynamespace/v1', 'property_by_category/(?P<id>\d+)',array(
 
                'methods'  => 'GET',
                'callback' => array($this, 'get_property_by_category'),
 
            ) );

              register_rest_route( 'mynamespace/v1', 'property_with_category',array(
   
                  'methods'  => 'GET',
                  'callback' => array($this, 'get_property_with_category'),
   
              ) );


              register_rest_route( 'mynamespace/v1', 'categorys_with_property',array(
 
                'methods'  => 'GET',
                'callback' => array($this, 'get_categorys_with_property'),
 
            ) );

              register_rest_route( 'mynamespace/v1', 'status_with_property',array(
 
                'methods'  => 'GET',
                'callback' => array($this, 'get_status_with_property'),
 
            ) );

            register_rest_route( 'mynamespace/v1', 'options_mortgage',array(
 
                'methods'  => 'GET',
                'callback' => array($this, 'get_options_mortgage'),
 
            ) );

            
 
     } );

  }
 
    public function get_latest_post(){
      global $wpdb;

        $args = array(
            'post_type'    => 'property',
            'meta_key'     => 'real_estate_property_price',
        );
        $query = new WP_Query( $args );

        return $query;

     }
 

     public function get_property(){
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=UTF-8");
      header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
      header("Access-Control-Max-Age: 3600");
      header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        global $wpdb;

       $query = "SELECT SQL_CALC_FOUND_ROWS  posts.ID as id,posts.post_author as id_user,posts.post_title as title_property,posts.post_content as contenu_property,A.meta_value as prix,B.meta_value as adresse,C.meta_value as price_short,F.meta_value as ID_image,E.guid as url_image,G.meta_value as ID_agent  FROM wp_posts as posts  INNER JOIN wp_postmeta as A ON ( posts.ID = A.post_id ) INNER JOIN wp_postmeta as B ON ( posts.ID = B.post_id ) INNER JOIN wp_postmeta as C ON ( posts.ID = C.post_id ) INNER JOIN wp_postmeta as D ON ( posts.ID = D.post_id ) INNER JOIN wp_postmeta as F ON ( posts.ID = F.post_id ) INNER JOIN wp_postmeta as G ON ( posts.ID = G.post_id ) INNER JOIN wp_posts as E ON ( F.meta_value = E.ID ) WHERE ( \n  A.meta_key = 'real_estate_property_price'\n) AND ( \n  B.meta_key = 'real_estate_property_address'\n) AND ( \n  C.meta_key = 'real_estate_property_price_short'\n) AND ( \n  D.meta_key = 'real_estate_property_price_unit'\n) AND ( \n  G.meta_key = 'real_estate_property_agent'\n) AND ( \n  F.meta_key = '_thumbnail_id'\n) AND posts.post_type = 'property' AND (posts.post_status = 'publish' OR posts.post_status = 'expired' OR posts.post_status = 'hidden') GROUP BY posts.ID ORDER BY posts.post_date DESC LIMIT 0, 10";

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_property', 'empty_property', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }

      public function get_one_property($id_property){
        global $wpdb;

       $query = "SELECT SQL_CALC_FOUND_ROWS  posts.ID as id,posts.post_author as id_user,posts.post_title as title_property,posts.post_content as description,A.meta_value as prix,B.meta_value as adresse,C.meta_value as price_short,F.meta_value as ID_image,E.guid as url_image, G.meta_value as ID_agent, H.meta_value as size_room,J.meta_value as location, K.post_title as nom_agent,Bathrooms.meta_value as bathrooms,Bedrooms.meta_value as bedrooms,Country.meta_value as country,Zip.meta_value as postalcode_zip,Rooms.meta_value as rooms,Land.meta_value as land_area,Garage.meta_value as garage FROM wp_posts as posts  INNER JOIN wp_postmeta as A ON ( posts.ID = A.post_id ) INNER JOIN wp_postmeta as B ON ( posts.ID = B.post_id ) INNER JOIN wp_postmeta as C ON ( posts.ID = C.post_id ) INNER JOIN wp_postmeta as D ON ( posts.ID = D.post_id ) INNER JOIN wp_postmeta as F ON ( posts.ID = F.post_id ) INNER JOIN wp_postmeta as G ON ( posts.ID = G.post_id ) INNER JOIN wp_postmeta as H ON ( posts.ID = H.post_id ) INNER JOIN wp_postmeta as J ON ( posts.ID = J.post_id ) INNER JOIN wp_postmeta as Bathrooms ON ( posts.ID = Bathrooms.post_id ) INNER JOIN wp_postmeta as Bedrooms ON ( posts.ID = Bedrooms.post_id ) INNER JOIN wp_postmeta as Country ON ( posts.ID = Country.post_id ) INNER JOIN wp_postmeta as Zip ON ( posts.ID = Zip.post_id ) INNER JOIN wp_postmeta as Rooms ON ( posts.ID = Rooms.post_id ) INNER JOIN wp_postmeta as Land ON ( posts.ID = Land.post_id ) INNER JOIN wp_postmeta as Garage ON ( posts.ID = Garage.post_id ) INNER JOIN wp_posts as E ON ( F.meta_value = E.ID )  INNER JOIN wp_posts as K ON ( G.meta_value = K.ID ) WHERE ( \n  A.meta_key = 'real_estate_property_price'\n) AND ( \n  B.meta_key = 'real_estate_property_address'\n) AND ( \n  C.meta_key = 'real_estate_property_price_short'\n) AND ( \n  D.meta_key = 'real_estate_property_price_unit'\n) AND ( \n  G.meta_key = 'real_estate_property_agent'\n) AND ( \n  H.meta_key = 'real_estate_property_size'\n) AND ( \n  J.meta_key = 'real_estate_property_location'\n) AND ( \n  F.meta_key = '_thumbnail_id'\n) AND ( \n  Bathrooms.meta_key = 'real_estate_property_bathrooms'\n) AND ( \n  Bedrooms.meta_key = 'real_estate_property_bedrooms'\n) AND ( \n  Country.meta_key = 'real_estate_property_country'\n) AND ( \n  Zip.meta_key = 'real_estate_property_zip'\n) AND ( \n  Rooms.meta_key = 'real_estate_property_rooms'\n) AND ( \n  Land.meta_key = 'real_estate_property_land'\n) AND ( \n  Garage.meta_key = 'real_estate_property_garage'\n) AND posts.post_type = 'property' AND (posts.post_status = 'publish' OR posts.post_status = 'expired' OR posts.post_status = 'hidden') AND posts.ID=".$id_property['id']." GROUP BY posts.ID ORDER BY posts.post_date DESC LIMIT 0, 10";

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_property', 'empty_property', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }

     public function get_agents(){
        global $wpdb;

       $query = "SELECT SQL_CALC_FOUND_ROWS agents.ID,agents.post_title as nom_agent, H.meta_value as name_company,J.meta_value as email_agent, K.meta_value as call_mobile,L.meta_value as call_office FROM wp_posts as agents INNER JOIN wp_postmeta as H ON ( agents.ID = H.post_id ) INNER JOIN wp_postmeta as J ON ( agents.ID = J.post_id ) INNER JOIN wp_postmeta as K ON ( agents.ID = K.post_id ) INNER JOIN wp_postmeta as L ON ( agents.ID = L.post_id ) WHERE agents.post_type = 'agent'  AND ( \n  H.meta_key = 'real_estate_agent_company'\n) AND ( \n  J.meta_key = 'real_estate_agent_email'\n) AND ( \n  K.meta_key = 'real_estate_agent_mobile_number'\n) AND ( \n  L.meta_key = 'real_estate_agent_office_number'\n) GROUP BY agents.ID ";

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_agents', 'empty_agents', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }

     public function get_one_agent($id_agent){
        global $wpdb;

       $query = "SELECT SQL_CALC_FOUND_ROWS agents.ID,agents.post_title as nom_agent, H.meta_value as name_company,J.meta_value as email_agent, K.meta_value as call_mobile ,L.meta_value as call_office,M.meta_value as skype FROM wp_posts as agents INNER JOIN wp_postmeta as H ON ( agents.ID = H.post_id ) INNER JOIN wp_postmeta as J ON ( agents.ID = J.post_id ) INNER JOIN wp_postmeta as K ON ( agents.ID = K.post_id ) INNER JOIN wp_postmeta as L ON ( agents.ID = L.post_id ) INNER JOIN wp_postmeta as M ON ( agents.ID = M.post_id ) WHERE agents.post_type = 'agent' AND ( \n  H.meta_key = 'real_estate_agent_company'\n) AND ( \n  J.meta_key = 'real_estate_agent_email'\n) AND ( \n  K.meta_key = 'real_estate_agent_mobile_number'\n) AND ( \n  L.meta_key = 'real_estate_agent_office_number'\n) AND ( \n  M.meta_key = 'real_estate_agent_skype'\n) AND agents.ID=".$id_agent['id']." GROUP BY agents.ID ";

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_agent', 'empty_agent', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }


     public function get_categorys(){
        global $wpdb;

       $query = "SELECT wp_terms.term_id as id,wp_terms.name as name,A.description as description, A.term_taxonomy_id as taxonomy_id FROM wp_terms INNER JOIN wp_term_taxonomy as A ON ( wp_terms.term_id = A.term_id ) WHERE A.taxonomy = 'property-type' " ;

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_category', 'empty_category', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }

       public function get_status(){
          global $wpdb;

         $query = "SELECT wp_terms.term_id as id,wp_terms.name as name,A.description as description, A.term_taxonomy_id as taxonomy_id FROM wp_terms INNER JOIN wp_term_taxonomy as A ON ( wp_terms.term_id = A.term_id ) WHERE A.taxonomy = 'property-status' " ;

            $posts  = $wpdb->get_results($query);
            
            if (empty($posts)) {
                return new WP_Error( 'empty_status', 'empty_status', array('status' => 404) );
            }

            $response = new WP_REST_Response($posts);
            $response->set_status(200);
            return $response;

       }



     public function get_property_by_category($id_category){
        global $wpdb;

       $query = "SELECT SQL_CALC_FOUND_ROWS  posts.ID as id,posts.post_author as id_user,posts.post_title as title_property,posts.post_content as contenu_property,A.meta_value as prix,B.meta_value as adresse,C.meta_value as price_short,F.meta_value as ID_image,E.guid as url_image,G.meta_value as ID_agent  FROM wp_posts as posts  INNER JOIN wp_postmeta as A ON ( posts.ID = A.post_id ) INNER JOIN wp_postmeta as B ON ( posts.ID = B.post_id ) INNER JOIN wp_postmeta as C ON ( posts.ID = C.post_id ) INNER JOIN wp_postmeta as D ON ( posts.ID = D.post_id ) INNER JOIN wp_postmeta as F ON ( posts.ID = F.post_id ) INNER JOIN wp_postmeta as G ON ( posts.ID = G.post_id ) INNER JOIN wp_posts as E ON ( F.meta_value = E.ID ) INNER JOIN wp_term_relationships as relationships ON ( relationships.object_id = posts.ID ) WHERE ( \n  A.meta_key = 'real_estate_property_price'\n) AND ( \n  B.meta_key = 'real_estate_property_address'\n) AND ( \n  C.meta_key = 'real_estate_property_price_short'\n) AND ( \n  D.meta_key = 'real_estate_property_price_unit'\n) AND ( \n  G.meta_key = 'real_estate_property_agent'\n) AND ( \n  F.meta_key = '_thumbnail_id'\n) AND posts.post_type = 'property' AND (posts.post_status = 'publish' OR posts.post_status = 'expired' OR posts.post_status = 'hidden') AND relationships.term_taxonomy_id=".$id_category['id']." GROUP BY relationships.object_id ORDER BY posts.post_date DESC LIMIT 0, 10";

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_property', 'there is no property in this category', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }


     public function get_property_with_category(){
        global $wpdb;

       $query = "SELECT SQL_CALC_FOUND_ROWS  posts.ID as id,posts.post_author as id_user,posts.post_title as title_property,posts.post_content as contenu_property,A.meta_value as prix,B.meta_value as adresse,C.meta_value as price_short,F.meta_value as ID_image,E.guid as url_image,G.meta_value as ID_agent,relationships.term_taxonomy_id as taxonomy_id  FROM wp_posts as posts  INNER JOIN wp_postmeta as A ON ( posts.ID = A.post_id ) INNER JOIN wp_postmeta as B ON ( posts.ID = B.post_id ) INNER JOIN wp_postmeta as C ON ( posts.ID = C.post_id ) INNER JOIN wp_postmeta as D ON ( posts.ID = D.post_id ) INNER JOIN wp_postmeta as F ON ( posts.ID = F.post_id ) INNER JOIN wp_postmeta as G ON ( posts.ID = G.post_id ) INNER JOIN wp_posts as E ON ( F.meta_value = E.ID ) INNER JOIN wp_term_relationships as relationships ON ( relationships.object_id = posts.ID ) WHERE ( \n  A.meta_key = 'real_estate_property_price'\n) AND ( \n  B.meta_key = 'real_estate_property_address'\n) AND ( \n  C.meta_key = 'real_estate_property_price_short'\n) AND ( \n  D.meta_key = 'real_estate_property_price_unit'\n) AND ( \n  G.meta_key = 'real_estate_property_agent'\n) AND ( \n  F.meta_key = '_thumbnail_id'\n) AND posts.post_type = 'property' AND (posts.post_status = 'publish' OR posts.post_status = 'expired' OR posts.post_status = 'hidden') GROUP BY relationships.object_id ORDER BY posts.post_date DESC LIMIT 0, 10";

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_property', 'empty_property', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }

     //Utilisé dans overview
      public function get_categorys_by_property($id_property){
        global $wpdb;

       $query = "SELECT wp_terms.term_id as id,wp_terms.name as name,A.description as description, A.term_taxonomy_id as taxonomy_id FROM wp_terms INNER JOIN wp_term_taxonomy as A ON ( wp_terms.term_id = A.term_id ) INNER JOIN wp_term_relationships as B ON ( A.term_taxonomy_id = B.term_taxonomy_id ) WHERE A.taxonomy = 'property-type' AND B.object_id=".$id_property['id']."  " ;

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_category', 'there is no category in this property', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }


     public function get_status_by_property($id_property){
        global $wpdb;

       $query = "SELECT wp_terms.term_id as id,wp_terms.name as name,A.description as description, A.term_taxonomy_id as taxonomy_id FROM wp_terms INNER JOIN wp_term_taxonomy as A ON ( wp_terms.term_id = A.term_id ) INNER JOIN wp_term_relationships as B ON ( A.term_taxonomy_id = B.term_taxonomy_id ) WHERE A.taxonomy = 'property-status' AND B.object_id=".$id_property['id']."  " ;

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_status', 'empty_status', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }


     //Utilisé dans onepropety.page.ts
     //Utilisé dans overview
      public function get_categorys_with_property(){
        global $wpdb;

       $query = "SELECT wp_terms.term_id as id,wp_terms.name as name,A.description as description, A.term_taxonomy_id as taxonomy_id,B.object_id as id_property FROM wp_terms INNER JOIN wp_term_taxonomy as A ON ( wp_terms.term_id = A.term_id ) INNER JOIN wp_term_relationships as B ON ( A.term_taxonomy_id = B.term_taxonomy_id ) WHERE A.taxonomy = 'property-type' " ;

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_category', 'empty_category', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }


     public function get_status_with_property(){
        global $wpdb;

       $query = "SELECT wp_terms.term_id as id,wp_terms.name as name,A.description as description, A.term_taxonomy_id as taxonomy_id,B.object_id as id_property FROM wp_terms INNER JOIN wp_term_taxonomy as A ON ( wp_terms.term_id = A.term_id ) INNER JOIN wp_term_relationships as B ON ( A.term_taxonomy_id = B.term_taxonomy_id ) WHERE A.taxonomy = 'property-status' " ;

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_status', 'empty_status', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }


     public function get_options_mortgage(){
        global $wpdb;

       $query = "SELECT * FROM `wp_options` WHERE `option_name`='lidd_mc_options'" ;

          $posts  = $wpdb->get_results($query);
          
          if (empty($posts)) {
              return new WP_Error( 'empty_options', 'empty_options', array('status' => 404) );
          }

          $response = new WP_REST_Response($posts);
          $response->set_status(200);
          return $response;

     }



     // public function get_agents(){
     //    global $wpdb;

     //   $query = "SELECT SQL_CALC_FOUND_ROWS agents.ID,agents.post_title as nom_agent,G.guid as url_image, H.meta_value as name_company,J.meta_value as email_agent, K.meta_value as call_mobile,L.meta_value as call_office FROM wp_posts as agents INNER JOIN wp_postmeta as F ON ( agents.ID = F.post_id ) INNER JOIN wp_posts as G ON ( G.ID = F.meta_value ) INNER JOIN wp_postmeta as H ON ( agents.ID = H.post_id ) INNER JOIN wp_postmeta as J ON ( agents.ID = J.post_id ) INNER JOIN wp_postmeta as K ON ( agents.ID = K.post_id ) INNER JOIN wp_postmeta as L ON ( agents.ID = L.post_id ) WHERE agents.post_type = 'agent' AND ( \n  F.meta_key = '_thumbnail_id'\n) AND ( \n  H.meta_key = 'real_estate_agent_company'\n) AND ( \n  J.meta_key = 'real_estate_agent_email'\n) AND ( \n  K.meta_key = 'real_estate_agent_mobile_number'\n) AND ( \n  L.meta_key = 'real_estate_agent_office_number'\n) GROUP BY agents.ID ";

     //      $posts  = $wpdb->get_results($query);
          
     //      if (empty($posts)) {
     //          return new WP_Error( 'empty_category', 'there is no post in this category', array('status' => 404) );
     //      }

     //      $response = new WP_REST_Response($posts);
     //      $response->set_status(200);
     //      return $response;

     // }

     // public function get_one_agent($id_agent){
     //    global $wpdb;

     //   $query = "SELECT SQL_CALC_FOUND_ROWS agents.ID,agents.post_title as nom_agent,G.guid as url_image, H.meta_value as name_company,J.meta_value as email_agent, K.meta_value as call_mobile ,L.meta_value as call_office FROM wp_posts as agents INNER JOIN wp_postmeta as F ON ( agents.ID = F.post_id ) INNER JOIN wp_posts as G ON ( G.ID = F.meta_value ) INNER JOIN wp_postmeta as H ON ( agents.ID = H.post_id ) INNER JOIN wp_postmeta as J ON ( agents.ID = J.post_id ) INNER JOIN wp_postmeta as K ON ( agents.ID = K.post_id ) INNER JOIN wp_postmeta as L ON ( agents.ID = L.post_id ) WHERE agents.post_type = 'agent' AND ( \n  F.meta_key = '_thumbnail_id'\n) AND ( \n  H.meta_key = 'real_estate_agent_company'\n) AND ( \n  J.meta_key = 'real_estate_agent_email'\n) AND ( \n  K.meta_key = 'real_estate_agent_mobile_number'\n) AND ( \n  L.meta_key = 'real_estate_agent_office_number'\n) AND agents.ID=".$id_agent['id']." GROUP BY agents.ID ";

     //      $posts  = $wpdb->get_results($query);
          
     //      if (empty($posts)) {
     //          return new WP_Error( 'empty_category', 'there is no post in this category', array('status' => 404) );
     //      }

     //      $response = new WP_REST_Response($posts);
     //      $response->set_status(200);
     //      return $response;

     // }
    

     }

new Home_market_Rest_api();

?>