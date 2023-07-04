<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang, Schema, DB, Validator;

class LanguageController extends Controller
{
	private $table;
	private $id; 
	private $setting; 
	private $default; 

    # Create a new controller instance
    public function __construct()
    {
        $this->middleware(['auth', 'roles:superadmin']);
        $this->table   = Lang::$table;
        $this->id      = Lang::$id;
        $this->setting = Lang::$setting;
        $this->default = Lang::$default;
    }


    public function setting()
    { 
        $title     = Lang::label('Setting');
        $setting   = DB::table($this->table)->value($this->setting);
        $languages = Lang::languageList();
        return view('admin.language.setting', compact('title', 'setting', 'languages'));  
    }

    public function addLanguage(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:20',
        ]); 

        if ($validator->fails()) 
        {
            return back()
                ->withInput()
                ->withErrors($validator); 
        } 
        else 
        { 
			if (Schema::hasColumn($this->table, $request->name))
			{
                alert()->error(Lang::label('Language Already Exists!'));
        		return back(); 
			}
			else
			{
				$txt = $request->name;
				Schema::table($this->table, function($schema) use($txt)
				{
				    $schema->string($txt)->nullable();
				});
                alert()->success(Lang::label("Save Successful!"));
	        	return back();
			}
        }
    }

    public function defaultLanguage(Request $request)
    {
		if (Schema::hasColumn($this->table, $request->name))
		{

            if (DB::table($this->table)->count() == 0)
            {
                DB::table($this->table)
                    ->insert(array(
                        $this->setting => $request->name
                    ));
            }
            else
            {
                DB::table($this->table)
                    ->update(array(
                        $this->setting => $request->name
                    ));
            }

            alert()->success(Lang::label("Default Language Activated!"));
			return back();
		}
		else
		{
            alert()->error(Lang::label("Language is not exists!"));
			return back();  
		}
    }

    public function deleteLanguage(Request $request)
    {
		$txt = $request->name; 

        if ($txt == $this->default)
        {
            alert()->error(Lang::label("You have no permission!"));
    		return back(); 
        }
        
		if (Schema::hasColumn($this->table, $request->name))
		{
			Schema::table($this->table, function($schema) use($txt)
			{
			    $schema->dropColumn($txt);
			});
            alert()->success(Lang::label("Delete Successful!"));
			return back();
		}
		else
		{
            alert()->success(Lang::label("Language is not exists!"));
			return back();  
		}
    }

    public function label()
    {
        $title     = Lang::label('Label');
        $columns   = Lang::languageList();
        $languages = DB::table($this->table)->orderBy($this->default,'asc')->paginate(50);
        return view('admin.language.label', compact('title', 'columns', 'languages'));
    }


    public function addLabel(Request $request)
    { 
    	$dataSet[$this->setting] = null;
    	if (is_array($request->language) && count($request->language))
    	{
    		foreach ($request->language as $key => $value) 
    		{
    			$dataSet[$key] = $value;
    		} 

    		DB::table($this->table)->insert($dataSet);
            alert()->success(Lang::label("Save Successful!"));
        	return back();
    	}
    	else
    	{
            alert()->error(Lang::label('Please Try Again.'));
        	return back(); 
    	} 
    }


    public function updateLabel(Request $request)
    { 
	    for ($i=0; $i<count($request->id); $i++) 
	    {
	        DB::table($this->table)
	            ->where('id',$request->id[$i])
	            ->update([
	                $request->language[$i] => $request->data[$i],
	        	]);
	    } 
        alert()->success(Lang::label('Update Successful!'));
        return back();
    }


    public function deleteLabel(Request $request)
    {
    	if (DB::table($this->table)->where($this->id, $request->id)->delete())
		{
            alert()->success(Lang::label('Delete Successful!'));
            return back();
		}
        alert()->error(Lang::label('Please Try Again.'));
        return back();
    }
 
}
