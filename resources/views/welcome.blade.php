@extends('layouts/landing')

@section('content')
<div x-data="authModals()" x-init="initRoute(); setupPopStateHandler() ">

    @include('components.landing-options')

    <div x-show="showLoginModal" x-cloak>
        @include('auth.login')
    </div>

    <div x-show="showRegisterModal" x-cloak>
        @include('auth.register')
    </div>


</div>

<script>
    function authModals() {
        return {
            showLoginModal: false,
            showRegisterModal: false,

            initRoute() {
                const path = window.location.pathname;

                if (path === '/login') {
                    this.showLoginModal = true;
                }

                if (path === '/register' || sessionStorage.getItem('fromRegister') === 'true') {
                    sessionStorage.removeItem('fromRegister');
                    this.showRegisterModal = true;
                }
            },

            openLoginModal() {
                this.showLoginModal = true;
                history.pushState({}, '', '/login');
            },

            openRegisterModal() {
                this.showRegisterModal = true;
                history.pushState({}, '', '/register');
            },

            closeModals() {
                this.showLoginModal = false;
                this.showRegisterModal = false;
                history.pushState({}, '', '/');
            },

            setupPopStateHandler() {
                window.onpopstate = () => {
                    this.initRoute();
                    if (this.showLoginModal || this.showRegisterModal) {
                        this.closeModals();
                    }
                };
            }
        }
    }
</script>
@endsection