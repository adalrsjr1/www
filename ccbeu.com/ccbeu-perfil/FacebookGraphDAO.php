<?php
   	class FacebookGraphDAO
   	{
   		private $facebook;
		private $user;
		
		public function FacebookGraphDAO($facebook)
		{
			$this->facebook = $facebook;
			//$this->user = $facebook->getUser();
		}
		
		
		public function getFacebook()
		{
			return $this->facebook;
		}
		
		public function getUser()
		{
			return $this->user;
		}
		
		public function api($comm)
		{
			return $this->facebook->api($comm);
		}
		
		private function fql($query)
		{
			$params = array
			(
				'method' => 'fql.query',
				'query' => $query, 
			);
			
			return $this->api($params);
		}
		
		public function userSex()
		{
			$query = 'SELECT sex FROM user WHERE uid=me()';
			$result = $this->fql($query);
			return $result[0]['sex'];
		}
		
		public function userLike($uid)
		{
			//215684038469835
			
			$query = 'SELECT page_id, name, type FROM page WHERE page_id in(
						SELECT page_id, type, profile_section FROM page_fan WHERE uid=me() AND page_id='.$uid.')'; 
			return $result = $this->fql($query);
		}
		
		public function getProfile()
		{
			$user_profile = $this->api('/me');
			return $user_profile;
		}
			
		public function getMe()
		{
			$query = 'SELECT uid, name, pic FROM user WHERE uid=me()';
			$me = $this->fql($query);
			return $me;
		}
				
		public function getFeed($limit=1000)
		{
			$query = 'SELECT actor_id, message FROM stream WHERE source_id = me() LIMIT '.$limit;
			
			$user_feed = $this->fql($query);
			return $user_feed;
		}
		
		public function getFamily()
		{
			$user_family = $this->api('/me?fields=family');
			return $user_family;
		}
		
		public function getFriendsRanking()
		{
			$query = 'SELECT uid, name, mutual_friend_count, significant_other_id, pic_big FROM user 
					    WHERE uid IN(SELECT significant_other_id FROM user WHERE uid=me()) 
					        OR uid IN(SELECT uid FROM family WHERE profile_id=me() LIMIT 3) 
					        OR uid IN(SELECT uid FROM user WHERE uid IN(SELECT uid2 FROM friend WHERE uid1=me() ) 
					                      ORDER BY mutual_friend_count desc LIMIT 20)
					  LIMIT 20';
			$data = $this->fql($query);
			return $data;
		}
		
		// based on mutual friends count
		public function getBestFriends($limit)
		{
			// fql
			//SELECT name,mutual_friend_count FROM user WHERE uid IN(
			//SELECT uid2 FROM friend WHERE uid1=me())
			$query = 'SELECT uid,name,mutual_friend_count FROM user WHERE uid IN(
					SELECT uid2 FROM friend WHERE uid1='.$this->getUser().') ';
			
			// usar esse fql para pegar os 10 melhores amigos
			 /*
			 SELECT uid, name, mutual_friend_count, significant_other_id FROM user 
    WHERE uid IN(SELECT uid FROM family WHERE profile_id=me() LIMIT 3) 
OR uid IN(SELECT significant_other_id FROM user WHERE uid=me()) 
OR uid IN(SELECT uid FROM user WHERE uid IN(SELECT uid2 FROM friend WHERE uid1=me() ) ORDER BY mutual_friend_count desc)
LIMIT 10*/
			
			if($limit > 0)
				$query = $query.'LIMIT '.$limit;		
			
			$data = $this->fql($query);
			
			foreach ($data as $key => $value) {
				$mutual[$key] = $value['mutual_friend_count'] ;
				$name[$key] = $value['name'];
			}
			
			array_multisort($mutual,SORT_DESC,$name,SORT_ASC,$data);
			
			return $data;
		}
		
		
		public function getFriends($limit)
		{
			$friends = '';
			if($limit > 0)
			{
				$friends = '/me?fields=friends.limit('.$limit.')';
			}
			else 
			{
				$friends = '/me?fields=friends';
			}
			$user_friends = $this->api($friends);
			return $user_friends;
		}		
	
		public function getFriendUID($uid)
		{
			$param = array(
				'method' => 'fql.query',
				'query' => "SELECT uid2 FROM friend WHERE uid1 = me() AND uid2 =".$uid
			);
			
			return $this->api($params);
		}
	
		public function getLikes($id, $limit=50)
		{
			$query = 'SELECT page_id, name, type FROM page WHERE page_id in(
						SELECT page_id, type, profile_section FROM page_fan WHERE uid='.$id.') LIMIT '.$limit;
			
			$data = $this->fql($query);
			return $data;
		}
	
		/*public function getLikes($id)
		{
			$user_likes = $this->api('/'.$id.'?fields=likes.limit(1000)');
			return $user_likes;
		}*/
		
		public function tagPhoto($photo_id, $ids)
		{
			 $tag_params = array(
	            'to'       => '100001825401172',
	            'tag_text' => 'Sample tag text',
	            'x'        => 0,
	            'y'        => 0
	        );
			$this->$facebook->api('/' . $photo_id . '/tags', 'POST', $tag_params);
		}
		
		public function uploadPhoto($msg, $path, $tags)
		{
			$ids = array();
			foreach ($tags as $key) {
				$uid = $key['uid'];
				array_push($ids,array('tag_uid'=>$uid, 'x'=>0, 'y'=>0));
			}

			$access_token = $this->facebook->getAccessToken();	

            # Upload photo to the album we've created above:
            $image_absolute_url = 'http://www.ccbeu.com/app/ccbeu-perfil/page/'.$path;

            $photo_details = array();
            $photo_details['access_token']  = $access_token;
            $photo_details['url']           = $image_absolute_url;                        # Use this to upload image using an Absolute URL.
            $photo_details['message']       = $msg;
			$photo_details['tags']			= $ids;
			//$photo_details['tags']			= array(array('tag_uid'=>'100001825401172', 'x'=>0, 'y'=>0));
            $upload_photo = $this->facebook->api('/me/photos', 'post', $photo_details);
			
			return $upload_photo;
			
			/*$this->facebook->setFileUploadSupport(true);
			$args = array('message' => $msg);
			$args['image'] = '@' . realpath('http://www.ccbeu.com/app/ccbeu-perfil/page/'.$path);
			
			$data = $this->api('/me/photos', 'post', $args);
			print_r($data);*/
	
		}
		
		public function getSignificantOther()
		{
			$user_sig_other = $this->api('/me?fields=significant_other');
			return $user_sig_other;
		}
		
		public function getFriendFeed($uidFriend, $limit)
		{
			$feed = '';
			if($limit > 0)
			{
				$feed = '/'.$uidFriend.'?fields=feed.limit('.$limit.').fields(message)';
			}
			else 
			{
				$feed = '/'.$uidFriend.'?fields=feed.fields(message)';
			}
			
			$user_feed = $this->api($feed);
			
			
			return $user_feed;
			
		}
		
		public function getAvatar($id, $w, $h)
		{
			$user_pic = $this->api('/'.$id.'?fields=picture.width('.$w.').height('.$h.')');
			return $user_pic;
		}
		
		public function getRelationship()
		{
			$user_relationship = $this->api('me?fields=relationship_status');
			return $user_relationship;
		}
   	}
   	
?>