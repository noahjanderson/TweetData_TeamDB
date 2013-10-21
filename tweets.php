<?php
class Tweets extends CI_Model {
	
	
	
	/*
	 * 
	 * Construct the Tweets object
	 * Include Mongo libarary
	 * Change to TweetData Database
	 * 
	 */
	public function __construct(){
		parent::__construct();
		$this->load->library('Mongo_db');
		$this->mongo_db->switch_db('TweetData');
	}
	
	
	/*
	 * create_index
	 * 
	 * Input:	$keys = array of fields/keys, $options = array of
	 * 			Mongodb Options
	 * 
	 * Post:	Adds indexes to tweets collection based on
	 * 			- array of $keys where the KEY is the
	 * 			Field and the Value is whether should be 'asc' or 'desc'
	 * 			if left empty will default to 'asc'
	 * 			- array of options are Mongodb Options for example:
	 * 				'unique' => TRUE
	 */
	public function create_index(Array $keys, Array $options)
	{
			$this->mongo_db->add_index('tweets',$keys,$options);
			
	}
	
	/*
	 * create_tweets
	 *
	 * Input:	Tweet Object from JSON Object from Twitter
	 * Output:	False if failed, nothing if passed;
	 * 
	 * Pre:		Objects ready for addition to Tweets Collection
	 * Post:	Obejcts saved in Tweets Collection
	 */
	
	public function create_tweets(array $tweet){
		
		if (empty($tweet))
		{
			echo "No Tweets Available to Process - Check Twitter Parser" ;
			return false;
		}

		$this->mongo_db->insert('tweets',$tweet);
	}
	
	
	/*
	 * read_tweet
	 * 
	 * Input:	None
	 * Output:	False if Failed or array of all available
	 * 			tweet objects
	 * 
	 * Pre & Post:No Change
	 * 
	 */
	public function read_tweets()
	{
			return $this->mongo_db->get('tweets');
	}
	
	/*
	public function search_tweets(Array $search)
	{
		
		
	}
	
	
	public function search_mult_tweets()
	{
		
		
	}
	*/
	
	/*
	 * update_tweets
	 * 
	 * Input: Requires array, Key(field) => Value(value)
	 * Post: Returns TRUE from library Mongo_db.php => update_all() 
	 * 
	 * 
	 */
	public function update_tweets(Array $query)
	{
		return $this->mongo_db->update_all('tweets',$query,false);
		//false refers to fact it won't add a document if none available
	}
	
	/*
	 * delete_tweets_collection
	 * 
	 * Post:  Drops tweets table to start fresh
	 * 
	 */
	public function delete_tweets_collection()
	{
		$this->mongo_db->drop_collection('tweets');
	}
	
	public function delete_duplicates()
	{
		$tweets = $this->mongo_db->get('tweets');
		$i=$h=0;
		foreach ($tweets as $tweet)
		{
			//check if unique tweetid if yes add it to unique_keys
			//for next position check
			if (!in_array($unique_keys,$tweet['id'],true))
			{
				$unique_keys[$i] = $tweet['id'];//tweet id only
				$i++;
			}//else put in non_unique_keys for deletion
			else 
			{
				$non_unique_keys[$h] = $tweet;//tweet object
				$h++;
			}
			
		}//end foreach with non_unique_keys available for deletion
		
		foreach ($non_unique_keys as $tweet)
		{	
			$this->mongo_db->delete_all($tweet['id']);
			$this->save_tweets($tweet);
		}
		
	}
	
}