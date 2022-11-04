<?php

/* For licensing terms, see /license.txt */

namespace Chamilo\CoreBundle\Entity;

use Chamilo\CoreBundle\Traits\TimestampableTypedEntity;
use Chamilo\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="agenda_event_invitation")
 * Add @ to the next lineactivating the agenda_collective_invitations configuration setting.
 * ORM\Entity()
 */
class AgendaEventInvitation
{
    use TimestampableTypedEntity;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Collection<int, AgendaEventInvitee>
     *
     * @ORM\OneToMany(targetEntity="AgendaEventInvitee", mappedBy="invitation", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $invitees;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Chamilo\UserBundle\Entity\User", inversedBy="resourceNodes")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $creator;

    public function __construct()
    {
        $this->invitees = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInvitees(): Collection
    {
        return $this->invitees;
    }

    public function setInvitees(Collection $invitees): AgendaEventInvitation
    {
        $this->invitees = $invitees;

        return $this;
    }

    public function addInvitee(AgendaEventInvitee $invitee): AgendaEventInvitation
    {
        $invitee->setInvitation($this);
        $this->invitees->add($invitee);

        return $this;
    }

    public function removeInviteeUser(User $user): AgendaEventInvitation
    {
        /** @var AgendaEventInvitee $invitee */
        $invitee = $this
            ->invitees
            ->filter(function (AgendaEventInvitee $invitee) use ($user) {
                return $invitee->getUser() === $user;
            })
            ->first();

        if ($invitee) {
            $this->invitees->removeElement($invitee);
            $invitee->setInvitation(null);
        }

        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): AgendaEventInvitation
    {
        $this->creator = $creator;

        return $this;
    }
}
