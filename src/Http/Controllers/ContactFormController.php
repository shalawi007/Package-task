<?php 

namespace Shalawi007\Contactform\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Shalawi007\Contactform\Models\Contact;
use Shalawi007\Contactform\Mail\InquiryEmail;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends BaseController
{
    public function create(){
        return view('contactform::create');
    }

    public function store(Request $request){
            $validated = $request->validate([
                'name'=> 'required|max:255',
                'email'=> 'required|max:255',
                'subject'=> 'required|max:255',
                'message'=> 'required|max:255',
            ]);

            Contact::create($validated);
            
            $admin_email = \config('contactform.admin_email');

            if($admin_email===null || $admin_email === ''){
                echo'The value of admin email not set';
            }else{
                Mail::to($admin_email)->send(new InquiryEmail($validated));
            }

            return back()->with('success','Your inquiry has been recieved');
    }
}

