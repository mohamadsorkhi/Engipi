<style>
@media (max-width: 900px) {
    .bp-auth-wrap {
        flex-direction: column;
        overflow-x: hidden;
    }

    .bp-auth-showcase {
        display: flex;
        order: -1;
        flex: none;
        width: 100%;
        min-height: 260px;
        padding: 1.5rem;
        background-size: cover;
        background-position: center;
    }

    .bp-auth-showcase-inner {
        max-width: 460px;
        text-align: center;
    }

    .bp-auth-showcase-inner > * {
        display: none !important;
    }

    .bp-auth-showcase-inner > .bp-auth-sc-brand,
    .bp-auth-showcase-inner > .bp-auth-sc-title {
        display: block !important;
    }

    .bp-auth-sc-brand {
        margin-bottom: .8rem;
    }

    .bp-auth-sc-title {
        margin: 0;
    }

    .bp-auth-showcase > .bp-auth-float {
        display: none;
    }
}

@media (max-width: 480px) {
    .bp-auth-showcase {
        min-height: 230px;
    }
}
</style>
