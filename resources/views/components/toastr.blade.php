@php
    // if(session()->has('success')) {
    //     $sucess = session()->get('success');
    //     if(is_array($success)) {
    //         foreach($success as $message) {
    //             toastr()->success($message.'asdasd');
    //         }
    //     } else {
    //         toastr()->success($message);
    //     }
    // }

    // if(session()->has('info')) {
    //     $info = session()->get('info');

    //     if(is_array($info)) {
    //         foreach($info as $message) {
    //             toastr()->info($message);
    //         }
    //     } else {
    //         toastr()->info($message);
    //     }
    // }

    // if(session()->has('warning')) {
    //     $warning = session()->get('warning');

    //     if(is_array($warning)) {
    //         foreach($warning as $message) {
    //             toastr()->warning($message);
    //         }
    //     } else {
    //         toastr()->warning($message);
    //     }
    // }
    if($errors->any()) {
        foreach($errors->all() as $message) {
            toastr()->error($message);
        }
    }
@endphp
