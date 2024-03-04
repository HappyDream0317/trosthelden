import ChatRepository from "./ChatRepository";
import PartnerRepository from "./PartnerRepository";
import BlockListRepository from "./BlockListRepository";
import EnvRepository from "./EnvRepository";
import B2BUserRepository from "./B2BUserRepository"
import PaymentRepository from "./PaymentRepository"

const repositories = {
    chat: ChatRepository,
    partners: PartnerRepository,
    blockList: BlockListRepository,
    env: EnvRepository,
    b2bUser: B2BUserRepository,
    payment: PaymentRepository,
};

export default {
    get: (name) => repositories[name],
};
