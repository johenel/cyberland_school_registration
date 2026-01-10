import React from 'react';
import {useModalStore} from "@/stores/modalStore";
import {useToastStore} from "@/stores/toastStore";

const Home = () => {
    const openModal = useModalStore((s) => s.openModal)
    const addToast = useToastStore((s) => s.addToast)

    return (
        <div>
            <button
                onClick={() => openModal(<p>This is a global modal content!</p>)}
                className="mt-4 px-4 py-2 bg-green-500 text-white rounded"
            >
                Open Modal
            </button>
            <button onClick={() => addToast({message: 'Test toast message', type: 'error'})}>
                Toast sample
            </button>
            <div className={"text-green-600"}>Home Page</div>
        </div>
    );
}

export default Home;
