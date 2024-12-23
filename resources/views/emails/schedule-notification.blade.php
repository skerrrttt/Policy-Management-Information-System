<!doctype html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>New Proposal Submission Schedule</title>
    <style>
         body {
          font-family: Helvetica, sans-serif;
          background-color: #f4f5f6;
          font-size: 16px;
          line-height: 1.3;
      }
      .container {
          margin: 0 auto;
          max-width: 600px;
      }
      .main {
          background: #ffffff;
          border: 1px solid #eaebed;
          border-radius: 16px;
      }
      .btn-primary a {
          background-color: #0867ec;
          border-color: #0867ec;
          color: #ffffff;
          padding: 12px 24px;
          text-decoration: none;
      }
      .btn-primary a:hover {
          background-color: #ec0867;
          border-color: #ec0867;
      }
    </style>
  </head>
  <body>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="main">
              <tr>
                <td class="wrapper">
                  <p>Dear Council Member,</p>
                  <p>A new schedule for proposal submission has been set up.</p>
                  <p>Details:</p>
                  <ul>
                    
                    <li>Submission Start: {{ $submissionStart }}</li>
                    <li>Submission End: {{ $submissionEnd }}</li>
                  </ul>
                  <p><a href="{{ url('/') }}" class="btn btn-primary">Visit Dashboard</a></p>
                  <p>Thank you!</p>
                </td>
              </tr>
            </table>
          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>
