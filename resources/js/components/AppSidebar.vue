<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Users, BookHeart, LayoutGrid, Activity } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const page = usePage();
const user = page.props.auth?.user;

// Helper function to check if user has a specific role
const hasRole = (roleName: string): boolean => {
    if (!user || !user.roles || !Array.isArray(user.roles)) return false;
    return user.roles.some(role => role.name === roleName);
};

// Helper function to check if user has a specific permission
const hasPermission = (permissionName: string): boolean => {
    if (!user || !user.permissions || !Array.isArray(user.permissions)) return false;
    return user.permissions.some(permission => permission.name === permissionName);
};

// Dashboard is visible to all authenticated users
const navItems = computed(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
            icon: LayoutGrid,
            show: true
        }
    ];

    // Users section - only for admins
    if (hasRole('admin') || hasPermission('create-user')) {
        items.push({
            title: 'Users',
            href: '/users',
            icon: Users,
            show: true
        });
    }

    // Patients section - for both admins and medics
    if (hasPermission('view-patient')) {
        items.push({
            title: 'Patients',
            href: '/patients',
            icon: BookHeart,
            show: true
        });
    }

    // Measures section - for both admins and medics
    if (hasPermission('view-measure')) {
        items.push({
            title: 'Measures',
            href: '/measures',
            icon: Activity,
            show: true
        });
    }

    return items.filter(item => item.show);
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="navItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
