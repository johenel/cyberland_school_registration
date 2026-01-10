import { create } from 'zustand'
import { nanoid } from 'nanoid'

export type Toast = {
    id: string
    message: string
    type?: 'success' | 'error' | 'info' | 'warning'
    duration?: number // milliseconds
}

type ToastState = {
    toasts: Toast[]
    addToast: (toast: Omit<Toast, 'id'>) => void
    removeToast: (id: string) => void
}

export const useToastStore = create<ToastState>((set) => ({
    toasts: [],
    addToast: (toast) =>
        set((state) => ({
            toasts: [...state.toasts, { id: nanoid(), duration: 3000, ...toast }],
        })),
    removeToast: (id) =>
        set((state) => ({
            toasts: state.toasts.filter((t) => t.id !== id),
        })),
}))
