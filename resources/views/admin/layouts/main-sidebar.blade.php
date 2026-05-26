<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header p-0">
        <a class="header-brand1" href="{{ route('dashboard.admin.adminHome') }}">
            <img src="{{ getFile($setting->where('key', 'logo')->first()->value) }}"
                class="header-brand-img mobile-icon" style="height: 3.7rem;" alt="logo">
        </a>
    </div>
    <ul class="side-menu">
        <li>
            <h3>{{ trns('elements') }}</h3>
        </li>

        <!-- Dashboard -->
        <li class="slide">
            <a class="side-menu__item {{ routeActive('dashboard.adminHome') }}" href="{{ route('dashboard.admin.adminHome') }}">
                <i class="fe fe-grid side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('home') }}</span>
            </a>
        </li>

        <!-- Companies -->
        <x-side-menu
            :can="'side_companies'"
            :icon="'fa fa-building'"
            :label="'colleges'"
            :routes="['dashboard.companies.index']"
            :mainRoute="'dashboard.companies.index'"
            :children="[]"
        />

        <!-- Admins -->
        <x-side-menu
            :can="'side_admins'"
            :icon="'fa fa-users'"
            :label="'admins'"
            :routes="['dashboard.admin.admins.index']"
            :mainRoute="'dashboard.admin.admins.index'"
            :children="[]"
        />

        <!-- Roles -->
        <x-side-menu
            :can="'side_roles'"
            :icon="'fa fa-user-shield'"
            :label="'roles'"
            :routes="['dashboard.admin.roles.index']"
            :mainRoute="'dashboard.admin.roles.index'"
            :children="[]"
        />

        <!-- People -->
        <x-side-menu
            :can="'side_people'"
            :icon="'fa fa-user'"
            :label="'people'"
            :routes="['dashboard.people.index']"
            :mainRoute="'dashboard.people.index'"
            :children="[]"
        />

        <!-- Sections -->
        <x-side-menu
            :can="'side_sections'"
            :icon="'fa fa-layer-group'"
            :label="'sections'"
            :routes="['dashboard.sections.index']"
            :mainRoute="'dashboard.sections.index'"
            :children="[]"
        />

        <!-- Courses -->
        <x-side-menu
            :can="'side_courses'"
            :icon="'fa fa-book-open'"
            :label="'courses'"
            :routes="['dashboard.courses.index']"
            :mainRoute="'dashboard.courses.index'"
            :children="[]"
        />

        

        <!-- Publishers -->
        <x-side-menu
            :can="'side_publishers'"
            :icon="'fa fa-industry'"
            :label="'publishers'"
            :routes="['dashboard.publishers.index']"
            :mainRoute="'dashboard.publishers.index'"
            :children="[]"
        />

        <!-- Books -->
        <x-side-menu
            :can="'side_books'"
            :icon="'fa fa-book'"
            :label="'the_books'"
            :routes="['dashboard.books.index']"
            :mainRoute="'dashboard.books.index'"
            :children="[
                [
                    'route' => 'dashboard.books.create',
                    'label' => 'add_new'
                ],
            ]"
        />

        <!-- Library Structure -->
        <x-side-menu
            :can="'side_library_structure'"
            :icon="'fa fa-sitemap'"
            :label="'library_structure'"
            :routes="['dashboard.library_structure.index']"
            :mainRoute="'dashboard.library_structure.index'"
            :children="[]"
        />

        <!-- Students -->
        <x-side-menu
            :can="'side_students'"
            :icon="'fa fa-user-graduate'"
            :label="'students'"
            :routes="['dashboard.students.index']"
            :mainRoute="'dashboard.students.index'"
            :children="[]"
        />

        <!-- Borrowings -->
        <x-side-menu
            :can="'side_borrowings'"
            :icon="'fa fa-hand-holding'"
            :label="'borrowings'"
            :routes="['dashboard.borrowings.index']"
            :mainRoute="'dashboard.borrowings.index'"
            :children="[
                [
                    'route' => 'dashboard.borrowing_books_by_status',
                    'param' => 'late',
                    'label' => 'late_borrowings'
                ],
                [
                    'route' => 'dashboard.borrowings.create',
                    'label' => 'add_new'
                ],
            ]"
        />

        <!-- Settings -->
        <x-side-menu
            :can="'side_settings'"
            :icon="'fa fa-cog'"
            :label="'settings'"
            :routes="['dashboard.admin.settings.edit']"
            :mainRoute="'dashboard.admin.settings.edit'"
            :children="$settings->unique('type')->filter(fn($item) => !in_array($item->type, ['payment', 'hidden']))->map(fn($item) => [
                'route' => 'dashboard.admin.settings.edit',
                'param' => $item->type,
                'label' => $item->type
            ])->values()->toArray()"
        />

        

        


    


        <li class="slide">
            @if(lang() == 'ar')
                <a class="side-menu__item " href="{{ route('dashboard.admin.change_language','en') }}">
                    <i class="fa fa-language m-2"></i>
                    <span class="side-menu__label"> English</span>
                </a>
            @else
                <a class="side-menu__item " href="{{ route('dashboard.admin.change_language','ar') }}">
                    <i class="fa fa-language m-2"></i>
                    <span class="side-menu__label">العربية</span>
                </a>
            @endif
        </li>

        <!-- Logout -->
        <li class="slide">
            <a class="side-menu__item text-danger {{ routeActive('dashboard.admin.logout') }}" href="{{ route('dashboard.admin.logout') }}">
                <i class="fe fe-log-out text-danger side-menu__icon m-2 "></i>
                <span class="side-menu__label">{{ trns('logout') }}</span>
            </a>
        </li>
    </ul>
</aside>
