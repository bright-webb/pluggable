    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plugin Repo</title>
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" integrity="sha512-b2QcS5SsA8tZodcDtGRELiGv5SaKSk1vDHDaQRda0htPYWZ6046lr3kJ5bAAQdpV2mmA/4v0wQF9MyU6/pDIAg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>

    <div class="container">
      <div class="row d-flex align-items-center justify-content-center w-100 vh-100">
        <div class="col-md-6">
        <div class="text-center">
            <h1>Find and install plugin for your laravel application</h1>
        </div>
        <br>
          <div class="card">
            <div class="card-header">
              <h4>Plugin Repo</h4>
            </div>

            <div class="card-body">
                <div class="search">
                    <form class="row" action="/home/search" method="post">
                        @csrf
                        <div class="form-group col-md-12">
                            <input type="text" class="form-control" name="query" id="search" placeholder="Search...">
                        </div>
                        <div class="cleafix"></div>
                        <br>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </form>
                </div>
                <br>
                <a href="/home/plugins">Browse plugins</a>
            </div>
        </div>
      </div>
    </div>

    </body>
    </html>
