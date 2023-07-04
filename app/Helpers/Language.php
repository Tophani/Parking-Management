<?php
namespace App\Helpers;
use DB, Schema;
 
class Language
{
	public static $table   = "language";
	public static $id      = "id"; 
	public static $setting = "setting"; 
	public static $default = "default"; 
	public static $labelExt = "&para;"; 

	public static function label($text = null, $myLang = null)
	{ 
		if (empty($text))
    	{
    		return "Label Here!"; 
    	} 
    	else
    	{   
			if (!Schema::hasTable(self::$table))
			{
				Schema::create(self::$table, function($schema)
				{
				    $schema->increments(self::$id);
				    $schema->string(self::$setting,20)->default(self::$default)->nullable();
				    $schema->string(self::$default,255)->nullable();
				});
				return self::clean_text($text);
			}
			else
			{ 
				$myLang = (!empty(session()->get('language'))?session()->get('language'):null);
				$myLang = (!empty($myLang)?$myLang:(DB::table(self::$table)->value(self::$setting)));

				if (!Schema::hasColumn(self::$table, $myLang))
				{
					return self::clean_text($text);
				}
				else
				{ 
					$data = DB::table(self::$table) 
						->where(self::$default, $text);

					if ($data->count())
					{
						return (!empty($data->value($myLang))?$data->value($myLang):(self::clean_text($text)));
					}
					else
					{
						return self::clean_text($text);
					}
				}
			}
    	} 
	}

	private static function clean_text($text = null)
	{
		// $text = preg_replace('/[^A-Za-z0-9\_]/',' ', $text);
		// $text = str_replace('_', ' ', $text);
		$text = ucfirst($text);
		return $text.self::$labelExt;
	}
  
    public static function languageList()
    {
        $columns = Schema::getColumnListing(self::$table);
        $list = array();
        foreach ($columns as $column) 
        {
        	if (!in_array($column, array(self::$id, self::$setting)))
        	$list[$column] = $column;
        }
        return $list;
    }


}
