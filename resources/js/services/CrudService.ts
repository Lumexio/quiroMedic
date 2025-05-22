import { router } from '@inertiajs/vue3';

export class CrudService {
    private basePath: string;

    constructor(modelName: string) {
        this.basePath = `/${modelName.toLowerCase()}`;
    }

    getAll() {
        return router.get(this.basePath);
    }

    get(id: number) {
        return router.get(`${this.basePath}/${id}`);
    }

    create(data: any, options = {}) {
        return router.post(this.basePath, data, options);
    }

    update(id: number, data: any, options = {}) {
        return router.put(`${this.basePath}/${id}`, data, options);
    }

    delete(id: number, options = {}) {
        return router.delete(`${this.basePath}/${id}`, options);
    }

    navigateToCreate() {
        return router.get(`${this.basePath}/create`);
    }

    navigateToEdit(id: number) {
        return router.get(`${this.basePath}/${id}/edit`);
    }

    navigateToList() {
        return router.get(this.basePath);
    }
}

// Create specific services for each model
export const usersService = new CrudService('users');
export const patientsService = new CrudService('patients');
export const measuresService = new CrudService('measures');
