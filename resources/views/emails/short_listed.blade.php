<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Job Starter</a>
    </div>
    <p style="font-size:1.1em">Hi {{ $details['graduate']['name'] }},</p>
    <p>
        you are shot listed by <b>{{ $details['company']['name'] }}</b> you can contact for further details
    </p>
    <p>Email: <a href="mailto:{{ $details['company']['email'] }}">{{ $details['company']['email'] }}</a></p>
    <p style="font-size:0.9em;">Regards,<br />{{ $details['company']['name'] }} </p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p>Job Starter Inc</p>
      <p>DHA Phase 2 </p>
      <p>Islamabad</p>
    </div>
  </div>
</div>
